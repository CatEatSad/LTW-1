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

// Get user's business
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id FROM businesses WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$business = $stmt->get_result()->fetch_assoc();

if (!$business) {
    die("No business found for this user");
}

$business_id = $business['id'];

// Update the upload directory to use relative path
$upload_dir = "../product_image";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_product'])) {
        // First insert the product to get the ID
        $stmt = $conn->prepare("INSERT INTO products (product_name, price, business_id, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdis", 
            $_POST['product_name'],
            $_POST['price'],
            $business_id,
            $_POST['description']
        );
        $stmt->execute();
        $product_id = $conn->insert_id;

        // Then handle the image
        $image_path = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = $product_id . '.' . $file_extension;
            $target_path = $upload_dir . '/' . $filename;
            $db_image_path = "../product_image/" . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                // Update the product with the image path
                $stmt = $conn->prepare("UPDATE products SET image=? WHERE id=?");
                $stmt->bind_param("si", $db_image_path, $product_id);
                $stmt->execute();
            }
        }
    }

    if (isset($_POST['edit_product'])) {
        $image_path = $_POST['current_image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = $_POST['product_id'] . '.' . $file_extension;
            $target_path = $upload_dir . '/' . $filename;
            $db_image_path = "../product_image/" . $filename;
            
            // Delete old image if exists and different from new path
            if (file_exists($_POST['current_image']) && $_POST['current_image'] != $db_image_path) {
                unlink($_POST['current_image']);
            }
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                $image_path = $db_image_path;
            }
        }

        $stmt = $conn->prepare("UPDATE products SET product_name=?, price=?, image=?, description=? WHERE id=? AND business_id=?");
        $stmt->bind_param("sdssii", 
            $_POST['product_name'],
            $_POST['price'],
            $image_path,
            $_POST['description'],
            $_POST['product_id'],
            $business_id
        );
        $stmt->execute();
    }

    if (isset($_POST['delete_product'])) {
        // Delete image file first
        $stmt = $conn->prepare("SELECT image FROM products WHERE id=? AND business_id=?");
        $stmt->bind_param("ii", $_POST['product_id'], $business_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        if ($result && file_exists($result['image'])) {
            unlink($result['image']);
        }

        // Then delete database record
        $stmt = $conn->prepare("DELETE FROM products WHERE id=? AND business_id=?");
        $stmt->bind_param("ii", $_POST['product_id'], $business_id);
        $stmt->execute();
    }
    
    header("Location: product.php");
    exit();
}

// Fetch only products belonging to user's business
$stmt = $conn->prepare("SELECT * FROM products WHERE business_id = ?");
$stmt->bind_param("i", $business_id);
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Add this helper function before the HTML
function displayStarRating($rating) {
    $output = '';
    $rating = floatval($rating);
    
    // Full stars
    for ($i = 1; $i <= floor($rating); $i++) {
        $output .= '<i class="fas fa-star text-warning"></i>';
    }
    
    // Half star
    if ($rating - floor($rating) >= 0.5) {
        $output .= '<i class="fas fa-star-half-alt text-warning"></i>';
    }
    
    // Empty stars
    for ($i = ceil($rating); $i < 5;$i++) {
        $output .= '<i class="far fa-star text-warning"></i>';
    }
    
    return $output;
}

?>


<?php include "header.php"?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Quản lý sản phẩm</h1>
                        <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                                    Thêm sản phẩm mới
                                </button>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Giá</th>
                                            <th>Hình ảnh</th>
                                            <th>Mô tả</th>
                                            <th>Đánh giá</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td><?php echo $product['id']; ?></td>
                                            <td><?php echo $product['product_name']; ?></td>
                                            <td><?php echo number_format($product['price']); ?>đ</td>
                                            <td><img src="<?php echo $product['image']; ?>" width="50"></td>
                                            <td style="max-width: 400px; " title="<?php echo htmlspecialchars($product['description']); ?>">
                                                <?php echo $product['description']; ?>
                                            </td>
                                            <td class="rating-cell"><?php echo displayStarRating($product['rating']); ?></td>
                                            <td class="action-cell">
                                                <button class="btn btn-link text-primary btn-sm" onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                    <button type="submit" name="delete_product" class="btn btn-link text-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Add Product Modal -->
                    <div class="modal fade" id="addProductModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Thêm sản phẩm mới</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Tên sản phẩm</label>
                                            <input type="text" class="form-control" name="product_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Giá</label>
                                            <input type="number" class="form-control" name="price" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Hình ảnh</label>
                                            <input type="file" class="form-control" name="image">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mô tả</label>
                                            <textarea class="form-control" name="description"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" name="add_product" class="btn btn-primary">Thêm</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Product Modal -->
                    <div class="modal fade" id="editProductModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Sửa sản phẩm</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" name="product_id" id="edit_product_id">
                                        <div class="mb-3">
                                            <label class="form-label">Tên sản phẩm</label>
                                            <input type="text" class="form-control" name="product_name" id="edit_product_name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Giá</label>
                                            <input type="number" class="form-control" name="price" id="edit_price" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Hình ảnh</label>
                                            <input type="file" class="form-control" name="image">
                                            <input type="hidden" name="current_image" id="edit_current_image">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mô tả</label>
                                            <textarea class="form-control" name="description" id="edit_description"></textarea>
                                        </div>
                                        <input type="hidden" name="business_id" value="<?php echo $_SESSION['business_id']; ?>">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" name="edit_product" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script>
        function editProduct(product) {
            document.getElementById('edit_product_id').value = product.id;
            document.getElementById('edit_product_name').value = product.product_name;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_current_image').value = product.image;
            document.getElementById('edit_description').value = product.description;
            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        }
        </script>

<?php include "footer.php"?>