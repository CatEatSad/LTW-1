<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../view/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "root", "LTW");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<?php include "header.php"?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-pie me-1"></i>
                                        Tỷ lệ số lượng bán ra của từng sản phẩm
                                    </div>
                                    <div class="card-body">
                                        <canvas id="salesPieChart" width="100%" height="50"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-pie me-1"></i>
                                        Tỷ lệ doanh thu của từng sản phẩm
                                    </div>
                                    <div class="card-body">
                                        <canvas id="revenuePieChart" width="100%" height="50"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Invoice Table Section -->
                        <div class="card mb-4 mt-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Danh sách hoá đơn
                            </div>
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
                                            echo "<td>{$row['shipping_status']}</td>";
                                            echo "<td>{$row['phone_number']}</td>";
                                            echo "<td>{$row['address']}</td>";
                                            echo "<td>
                                                    <button class='btn btn-info btn-sm view-btn' data-id='{$row['invoice_id']}' 
                                                            data-bs-toggle='modal' data-bs-target='#viewInvoiceModal'>Xem</button>
                                                </td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- View Invoice Modal -->
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
                                <div class="modal-footer"></div>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                
        <script>
            function getRandomColor() {
                return '#' + Math.floor(Math.random()*16777215).toString(16);
            }

            fetch('get_chart_data.php')
                .then(response => response.json())
                .then(data => {
                    // Sales Pie Chart
                    const salesCtx = document.getElementById('salesPieChart');
                    new Chart(salesCtx, {
                        type: 'pie',
                        data: {
                            labels: data.sales.map(item => item.product_name),
                            datasets: [{
                                data: data.sales.map(item => item.total_quantity),
                                backgroundColor: data.sales.map(() => getRandomColor())
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });

                    // Revenue Pie Chart
                    const revenueCtx = document.getElementById('revenuePieChart');
                    new Chart(revenueCtx, {
                        type: 'pie',
                        data: {
                            labels: data.revenue.map(item => item.product_name),
                            datasets: [{
                                data: data.revenue.map(item => item.total_revenue),
                                backgroundColor: data.revenue.map(() => getRandomColor())
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
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
                        document.getElementById('view_status').textContent = data.invoice.shipping_status;
                        
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

            function formatCurrency(amount) {
                return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);
            }
        </script>
<?php include "footer.php"?>