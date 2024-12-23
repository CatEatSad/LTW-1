<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "root", "LTW");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user and business data
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("SELECT * FROM businesses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$business = $stmt->get_result()->fetch_assoc();
// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_personal'])) {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, age=?, birth_date=?, gender=?, address=?, phone_number=? WHERE id=?");
        $stmt->bind_param("ssissssi", 
            $_POST['name'],
            $_POST['email'],
            $_POST['age'],
            $_POST['birth_date'],
            $_POST['gender'],
            $_POST['address'],
            $_POST['phone_number'],
            $user_id
        );
        $stmt->execute();
    }

    if (isset($_POST['update_business'])) {
        // Update business query to use correct table name
        $stmt = $conn->prepare("UPDATE businesses SET business_name=?, phone_number=?, address=?, email=?, industry=? WHERE user_id=?");
        $stmt->bind_param("sssssi",
            $_POST['business_name'],
            $_POST['business_phone'],
            $_POST['business_address'], 
            $_POST['business_email'],
            $_POST['industry'],
            $user_id
        );
        $stmt->execute();
    }

    if (isset($_POST['update_password'])) {
        if (password_verify($_POST['current_password'], $user['password'])) {
            $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param("si", $new_password, $user_id);
            $stmt->execute();
        }
    }
    
    header("Location: profile.php?updated=1");
    exit();
}
?>

<?php include "header.php"?>
            <div id="layoutSidenav_content">
                <main>
                   <div class="container-fluid px-4">
                    <h1 class="mt-4">Thông tin cá nhân</h1>
                    
                    <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success">Cập nhật thành công!</div>
                    <?php endif; ?>

                    <!-- Personal Information -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-user me-1"></i> Thông tin cá nhân</div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#personalModal">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </button>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-3">Họ và tên:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($user['name']) ?></dd>
                                
                                <dt class="col-sm-3">Email:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($user['email']) ?></dd>
                                
                                <dt class="col-sm-3">Tuổi:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($user['age']) ?></dd>
                                
                                <dt class="col-sm-3">Ngày sinh:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($user['birth_date']) ?></dd>
                                
                                <dt class="col-sm-3">Giới tính:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($user['gender']) ?></dd>
                                
                                <dt class="col-sm-3">Số điện thoại:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($user['phone_number']) ?></dd>
                                
                                <dt class="col-sm-3">Địa chỉ:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($user['address']) ?></dd>
                            </dl>
                        </div>
                    </div>

                    <!-- Business Information -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div><i class="fas fa-building me-1"></i> Thông tin doanh nghiệp</div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#businessModal">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </button>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-3">Tên doanh nghiệp:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($business['business_name']) ?></dd>
                                
                                <dt class="col-sm-3">Email:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($business['email']) ?></dd>
                                
                                <dt class="col-sm-3">Số điện thoại:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($business['phone_number']) ?></dd>
                                
                                <dt class="col-sm-3">Ngành nghề:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($business['industry']) ?></dd>
                                
                                <dt class="col-sm-3">Địa chỉ:</dt>
                                <dd class="col-sm-9"><?= htmlspecialchars($business['address']) ?></dd>
                            </dl>
                        </div>
                    </div>

                    <!-- Password Change -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-key me-1"></i>
                            Đổi mật khẩu
                        </div>
                        <div class="card-body">
                            <form method="POST">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label>Mật khẩu hiện tại</label>
                                        <input type="password" name="current_password" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Mật khẩu mới</label>
                                        <input type="password" name="new_password" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Xác nhận mật khẩu mới</label>
                                        <input type="password" name="confirm_password" class="form-control" required>
                                    </div>
                                </div>
                                <button type="submit" name="update_password" class="btn btn-primary">Đổi mật khẩu</button>
                            </form>
                        </div>
                    </div>
                    </div>
                </main>
            </div>
        </div>

<!-- Personal Information Modal -->
<div class="modal fade" id="personalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa thông tin cá nhân</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Họ và tên</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Tuổi</label>
                            <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($user['age']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Ngày sinh</label>
                            <input type="date" name="birth_date" class="form-control" value="<?= htmlspecialchars($user['birth_date']) ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Giới tính</label>
                            <select name="gender" class="form-control">
                                <option value="Nam" <?= $user['gender'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                                <option value="Nữ" <?= $user['gender'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone_number" class="form-control" value="<?= htmlspecialchars($user['phone_number']) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Địa chỉ</label>
                        <textarea name="address" class="form-control"><?= htmlspecialchars($user['address']) ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" name="update_personal" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Business Information Modal -->
<div class="modal fade" id="businessModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa thông tin doanh nghiệp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Tên doanh nghiệp</label>
                            <input type="text" name="business_name" class="form-control" value="<?= htmlspecialchars($business['business_name']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Email doanh nghiệp</label>
                            <input type="email" name="business_email" class="form-control" value="<?= htmlspecialchars($business['email']) ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Số điện thoại</label>
                            <input type="text" name="business_phone" class="form-control" value="<?= htmlspecialchars($business['phone_number']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Ngành nghề</label>
                            <input type="text" name="industry" class="form-control" value="<?= htmlspecialchars($business['industry']) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Địa chỉ doanh nghiệp</label>
                        <textarea name="business_address" class="form-control"><?= htmlspecialchars($business['address']) ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" name="update_business" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "footer.php"?>