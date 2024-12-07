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

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Charts - SB Admin</title>
        <link href="/assets/css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html" style="font-size: 45px;">MADCAT</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <a class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            </a>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="profile.php">Thông tin cá nhân</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="logout.php">Đăng Xuất</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav pt-5">
                            <a class="nav-link" href="dashboard.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt" style="font-size: 30px;"></i></div>
                                <span style="font-size: 30px;">Thống Kê</span>
                            </a>
                            <a class="nav-link" href="product.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-boxes-stacked" style="font-size: 30px;"></i></div>
                                <span style="font-size: 30px;">Sản phẩm</span>
                            </a>
                            <a class="nav-link" href="invoice.php">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-file-invoice-dollar" style="font-size: 30px;"></i></i></div>
                                <span style="font-size: 30px;">Hoá đơn</span>
                            </a>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-truck-field-un" style="font-size: 30px;"></i></div>
                                <span style="font-size: 20px;">Nhà cung cấp</span>
                                <div class="sb-sidenav-collapse-arrow ps-1"><i class="fas fa-angle-down" ></i></div>
                            </a>
                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="layout-static.html">Hợp đồng</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Quản lý sự cố</a>
                                </nav>
                            </div>
                        </div>
                    </div>
                    
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                   <div class="container-fluid px-4">
                    <h1 class="mt-4">Thông tin cá nhân</h1>
                    
                    <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success">Cập nhật thành công!</div>
                    <?php endif; ?>

                    <!-- Personal Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-user me-1"></i>
                            Thông tin cá nhân
                        </div>
                        <div class="card-body">
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
                                <button type="submit" name="update_personal" class="btn btn-primary">Cập nhật thông tin</button>
                            </form>
                        </div>
                    </div>

                    <!-- Business Information -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-building me-1"></i>
                            Thông tin doanh nghiệp
                        </div>
                        <div class="card-body">
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
                                <button type="submit" name="update_business" class="btn btn-primary">Cập nhật thông tin doanh nghiệp</button>
                            </form>
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="/assets/js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="/assets/js/datatables-simple-demo.js"></script>
    </body>
</html>
