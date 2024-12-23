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

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_invoice'])) {
        $customer_name = $_POST['customer_name'];
        $phone_number = $_POST['phone_number'];
        $address = $_POST['address'];
        $sql = "INSERT INTO invoices (customer_name, invoice_date, shipping_status, phone_number, address) 
                VALUES ('$customer_name', NOW(), 'pending', '$phone_number', '$address')";
        mysqli_query($conn, $sql);
        
        $invoice_id = mysqli_insert_id($conn);
        foreach ($_POST['product_id'] as $key => $product_id) {
            $quantity = $_POST['quantity'][$key];
            $sql = "INSERT INTO invoice_details (invoice_id, product_id, quantity) 
                    VALUES ($invoice_id, $product_id, $quantity)";
            mysqli_query($conn, $sql);
        }
    }

    if (isset($_POST['edit_invoice'])) {
        $invoice_id = $_POST['invoice_id'];
        $customer_name = $_POST['customer_name'];
        $phone_number = $_POST['phone_number'];
        $address = $_POST['address'];
        
        $sql = "UPDATE invoices SET customer_name='$customer_name', phone_number='$phone_number', 
                address='$address' WHERE invoice_id=$invoice_id";
        mysqli_query($conn, $sql);

        // Update invoice details
        mysqli_query($conn, "DELETE FROM invoice_details WHERE invoice_id=$invoice_id");
        foreach ($_POST['product_id'] as $key => $product_id) {
            $quantity = $_POST['quantity'][$key];
            $sql = "INSERT INTO invoice_details (invoice_id, product_id, quantity) 
                    VALUES ($invoice_id, $product_id, $quantity)";
            mysqli_query($conn, $sql);
        }
    }

    if (isset($_POST['update_status'])) {
        $invoice_id = $_POST['invoice_id'];
        $status = $_POST['status'];
        $sql = "UPDATE invoices SET shipping_status='$status' WHERE invoice_id=$invoice_id";
        mysqli_query($conn, $sql);
    }

    if (isset($_POST['delete_invoice'])) {
        $invoice_id = $_POST['invoice_id'];
        mysqli_query($conn, "DELETE FROM invoice_details WHERE invoice_id=$invoice_id");
        mysqli_query($conn, "DELETE FROM invoices WHERE invoice_id=$invoice_id");
    }
}
?>
<?php include "header.php"?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Quản lý hoá đơn</h1>
                        
                        <!-- Add Invoice Button -->
                        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addInvoiceModal">
                            Thêm hoá đơn mới
                        </button>

                        <!-- Invoice List -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Khách hàng</th>
                                            <th>Ngày</th>
                                            <th>Trạng thái</th>
                                            <th>Số điện thoại</th>
                                            <th>Địa chỉ</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $result = mysqli_query($conn, "SELECT * FROM invoices ORDER BY invoice_date DESC");
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>{$row['invoice_id']}</td>";
                                            echo "<td>{$row['customer_name']}</td>";
                                            echo "<td>{$row['invoice_date']}</td>";
                                            echo "<td>
                                                    <select class='form-select status-select' data-id='{$row['invoice_id']}'>
                                                        <option value='pending' ".($row['shipping_status']=='pending'?'selected':'').">Đang xử lý</option>
                                                        <option value='shipping' ".($row['shipping_status']=='shipping'?'selected':'').">Đang giao</option>
                                                        <option value='completed' ".($row['shipping_status']=='completed'?'selected':'').">Đã giao</option>
                                                        <option value='cancelled' ".($row['shipping_status']=='cancelled'?'selected':'').">Đã huỷ</option>
                                                    </select>
                                                </td>";
                                            echo "<td>{$row['phone_number']}</td>";
                                            echo "<td>{$row['address']}</td>";
                                            echo "<td>
                                                    <button class='btn btn-info btn-sm view-btn' data-id='{$row['invoice_id']}' 
                                                            data-bs-toggle='modal' data-bs-target='#viewInvoiceModal'>Xem</button>
                                                    <button class='btn btn-primary btn-sm edit-btn' data-id='{$row['invoice_id']}' 
                                                            data-bs-toggle='modal' data-bs-target='#editInvoiceModal'>Sửa</button>
                                                    <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['invoice_id']}'>Xoá</button>
                                                </td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Add Invoice Modal -->
                        <div class="modal fade" id="addInvoiceModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Thêm hoá đơn mới</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Tên khách hàng</label>
                                                    <input type="text" class="form-control" name="customer_name" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Số điện thoại</label>
                                                    <input type="text" class="form-control" name="phone_number" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Địa chỉ</label>
                                                <textarea class="form-control" name="address" required></textarea>
                                            </div>
                                            <div id="products-container">
                                                <h6>Sản phẩm</h6>
                                                <div class="product-item row mb-3">
                                                    <div class="col-md-8">
                                                        <select name="product_id[]" class="form-select" required>
                                                            <?php
                                                            $products = mysqli_query($conn, "SELECT * FROM products");
                                                            while ($product = mysqli_fetch_assoc($products)) {
                                                                echo "<option value='{$product['id']}'>{$product['product_name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number" name="quantity[]" class="form-control" placeholder="Số lượng" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-secondary" id="add-product">Thêm sản phẩm</button>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" name="add_invoice" class="btn btn-primary">Lưu</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Add Edit Invoice Modal -->
                        <div class="modal fade" id="editInvoiceModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Sửa hoá đơn</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST">
                                        <input type="hidden" name="invoice_id" id="edit_invoice_id">
                                        <div class="modal-body">
                                            <!-- Same structure as Add Invoice Modal -->
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Tên khách hàng</label>
                                                    <input type="text" class="form-control" name="customer_name" id="edit_customer_name" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Số điện thoại</label>
                                                    <input type="text" class="form-control" name="phone_number" id="edit_phone_number" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Địa chỉ</label>
                                                <textarea class="form-control" name="address" id="edit_address" required></textarea>
                                            </div>
                                            <div id="edit-products-container">
                                                <h6>Sản phẩm</h6>
                                                <div class="product-item row mb-3">
                                                    <div class="col-md-8">
                                                        <select name="product_id[]" class="form-select" required>
                                                            <?php
                                                            $products = mysqli_query($conn, "SELECT * FROM products");
                                                            while ($product = mysqli_fetch_assoc($products)) {
                                                                echo "<option value='{$product['id']}'>{$product['product_name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 d-flex">
                                                        <input type="number" name="quantity[]" class="form-control" placeholder="Số lượng" required>
                                                        <button type="button" class="btn btn-danger ms-2 remove-product">&times;</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-secondary" id="edit-add-product">Thêm sản phẩm</button>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                            <button type="submit" name="edit_invoice" class="btn btn-primary">Lưu thay đổi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Add View Invoice Modal before the closing container-fluid div -->
                        <div class="modal fade" id="viewInvoiceModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Chi tiết hoá đơn #<span id="view_invoice_id"></span></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p><strong>Khách hàng:</strong> <span id="view_customer_name"></span></p>
                                                <p><strong>Số điện thoại:</strong> <span id="view_phone_number"></span></p>
                                                <p><strong>Địa chỉ:</strong> <span id="view_address"></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Ngày tạo:</strong> <span id="view_date"></span></p>
                                                <p><strong>Trạng thái:</strong> <span id="view_status"></span></p>
                                            </div>
                                        </div>
                                        <hr>
                                        <h6>Chi tiết sản phẩm</h6>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Số lượng</th>
                                                    <th>Đơn giá</th>
                                                    <th>Thành tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody id="view_products"></tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3">Tổng tiền:</th>
                                                    <th id="view_total"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Confirmation Form -->
                    <form id="deleteForm" method="POST">
                        <input type="hidden" name="invoice_id">
                        <input type="hidden" name="delete_invoice" value="1">
                    </form>

                    <!-- Status Update Form -->
                    <form id="statusForm" method="POST" style="display: none;">
                        <input type="hidden" name="invoice_id">
                        <input type="hidden" name="status">
                        <input type="hidden" name="update_status" value="1">
                    </form>

                    <script>
                        // Handle status change
                        document.querySelectorAll('.status-select').forEach(select => {
                            select.addEventListener('change', function() {
                                const form = document.getElementById('statusForm');
                                form.querySelector('[name="invoice_id"]').value = this.dataset.id;
                                form.querySelector('[name="status"]').value = this.value;
                                form.submit();
                            });
                        });

                        // Handle delete
                        document.querySelectorAll('.delete-btn').forEach(button => {
                            button.addEventListener('click', function() {
                                if (confirm('Bạn có chắc muốn xoá hoá đơn này?')) {
                                    const form = document.getElementById('deleteForm');
                                    form.querySelector('[name="invoice_id"]').value = this.dataset.id;
                                    form.submit();
                                }
                            });
                        });

                        // Add product template function
                        function createProductRow(products) {
                            const row = document.createElement('div');
                            row.className = 'product-item row mb-3';
                            row.innerHTML = `
                                <div class="col-md-8">
                                    <select name="product_id[]" class="form-select" required>${products}</select>
                                </div>
                                <div class="col-md-4 d-flex">
                                    <input type="number" name="quantity[]" class="form-control" placeholder="Số lượng" required>
                                    <button type="button" class="btn btn-danger ms-2 remove-product">&times;</button>
                                </div>
                            `;
                            
                            // Add remove button handler
                            row.querySelector('.remove-product').addEventListener('click', function() {
                                row.remove();
                            });
                            
                            return row;
                        }

                        // Handle add product buttons
                        document.getElementById('add-product').addEventListener('click', function() {
                            const container = document.getElementById('products-container');
                            const productsHtml = container.querySelector('select').innerHTML;
                            container.appendChild(createProductRow(productsHtml));
                        });

                        document.getElementById('edit-add-product').addEventListener('click', function() {
                            const container = document.getElementById('edit-products-container');
                            const productsHtml = container.querySelector('select').innerHTML;
                            container.appendChild(createProductRow(productsHtml));
                        });

                        // Add initial remove button handlers
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelectorAll('.remove-product').forEach(button => {
                                button.addEventListener('click', function() {
                                    this.closest('.product-item').remove();
                                });
                            });
                        });

                        // Handle edit button clicks
                        document.querySelectorAll('.edit-btn').forEach(button => {
                            button.addEventListener('click', async function() {
                                const id = this.dataset.id;
                                try {
                                    const response = await fetch(`get_invoice.php?id=${id}`);
                                    const data = await response.json();
                                    
                                    // Fill the form
                                    document.getElementById('edit_invoice_id').value = data.invoice.invoice_id;
                                    document.getElementById('edit_customer_name').value = data.invoice.customer_name;
                                    document.getElementById('edit_phone_number').value = data.invoice.phone_number;
                                    document.getElementById('edit_address').value = data.invoice.address;
                                    
                                    // Load products
                                    const container = document.getElementById('edit-products-container');
                                    container.innerHTML = '<h6>Sản phẩm</h6>'; // Clear existing products
                                    
                                    // If no details, add one empty product row
                                    if (!data.details.length) {
                                        const productsHtml = container.querySelector('select').innerHTML;
                                        container.appendChild(createProductRow(productsHtml));
                                        return;
                                    }
                                    
                                    // Add each product
                                    data.details.forEach(detail => {
                                        const row = createProductRow(document.querySelector('select[name="product_id[]"]').innerHTML);
                                        row.querySelector('select').value = detail.product_id;
                                        row.querySelector('input').value = detail.quantity;
                                        container.appendChild(row);
                                    });
                                } catch (error) {
                                    console.error('Error fetching invoice data:', error);
                                    alert('Có lỗi xảy ra khi tải thông tin hoá đơn');
                                }
                            });
                        });

                        // Add view button handler
                        document.querySelectorAll('.view-btn').forEach(button => {
                            button.addEventListener('click', async function() {
                                const id = this.dataset.id;
                                try {
                                    const response = await fetch(`get_invoice.php?id=${id}`);
                                    const data = await response.json();
                                    // Fill invoice details
                                    document.getElementById('view_invoice_id').textContent = data.invoice.invoice_id;
                                    document.getElementById('view_customer_name').textContent = data.invoice.customer_name;
                                    document.getElementById('view_phone_number').textContent = data.invoice.phone_number;
                                    document.getElementById('view_address').textContent = data.invoice.address;
                                    document.getElementById('view_date').textContent = data.invoice.invoice_date;
                                    document.getElementById('view_status').textContent = getStatusText(data.invoice.shipping_status);
                                    
                                    // Fill products table
                                    const tbody = document.getElementById('view_products');
                                    tbody.innerHTML = '';
                                    let total = 0;
                                    
                                    data.details.forEach(item => {
                                        const subtotal = item.quantity * item.price;
                                        total += subtotal;
                                        tbody.innerHTML += `
                                            <tr>
                                                <td>${item.product_name}</td>
                                                <td>${item.quantity}</td>
                                                <td>${formatCurrency(item.price)}</td>
                                                <td>${formatCurrency(subtotal)}</td>
                                            </tr>
                                        `;
                                    });
                                    
                                    document.getElementById('view_total').textContent = formatCurrency(total);
                                } catch (error) {
                                    console.error('Error:', error);
                                    alert('Có lỗi xảy ra khi tải thông tin hoá đơn');
                                }
                            });
                        });

                        // Add helper functions
                        function getStatusText(status) {
                            const statusMap = {
                                'pending': 'Đang xử lý',
                                'shipping': 'Đang giao',
                                'completed': 'Đã giao',
                                'cancelled': 'Đã huỷ'
                            };
                            return statusMap[status] || status;
                        }

                        function formatCurrency(amount) {
                            return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
                        }
                    </script>
                </main>
 <?php include "footer.php"?>