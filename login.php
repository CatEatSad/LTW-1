<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
  header("Location: dashboard.php");
  exit();
}
if (isset($_GET['error'])) {
    $error = $_GET['error'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="description" content="">
  <meta name="keywords" content="">

  
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>
<body>
    <section class="vh-100 bg-primary" >
        <div class="container py-5 h-100">
          <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col col-xl-10">
              <div class="card" style="border-radius: 1rem;">
                <div class="row g-0">
                  <div class="col-md-6 col-lg-5 d-none d-md-block">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/img1.webp"
                      alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
                    </div>
                  <div class="col-md-6 col-lg-7 d-flex align-items-center">
                    <div class="card-body p-4 p-lg-5 text-black">
      
                      <!-- Thay thế phần form trong HTML của bạn với code sau -->
                    <form action="loginform.php" method="POST">
                      <?php if (isset($error)): ?>
                          <div class="alert alert-danger"><?php echo $error; ?></div>
                      <?php endif; ?>

                      <div class="d-flex align-items-center mb-3 pb-1">
                          <span class="h1 fw-bold mb-0 ms-3">MADCAT</span>
                      </div>

                      <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Đăng nhập vào tài khoản của bạn</h5>

                      <div class="form-outline mb-4">
                          <input type="email" name="email" id="email" class="form-control form-control-lg" required />
                          <label class="form-label" for="email">Địa chỉ email</label>
                      </div>

                      <div class="form-outline mb-4">
                          <input type="password" name="password" id="email" class="form-control form-control-lg" required />
                          <label class="form-label" for="email">Mật khẩu</label>
                      </div>

                      <div class="pt-1 mb-4">
                          <button class="btn btn-dark btn-lg btn-block" type="submit">Đăng nhập</button>
                      </div>

                      <a class="small text-muted" href="forgot-password.php">Quên mật khẩu?</a>
                      <p class="mb-5 pb-lg-2" style="color: #393f81;">Chưa có tài khoản? 
                          <a href="register.php" style="color: #393f81;">Đăng ký tại đây</a>
                      </p>
                    </form>
      
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <footer id="footer" class="footer dark-background">

        <div class="container footer-top">
          <div class="row gy-4">
            <div class="col-lg-4 col-md-6 footer-about">
              <a href="index.html" class="logo d-flex align-items-center">
                <span class="sitename">MadCat</span>
              </a>
              <div class="footer-contact pt-3">
                <p>96A Đường Trần Phú </p>
                <p>P. Mộ Lao, Hà Đông, Hà Nội</p>
                <p class="mt-3"><strong>Phone:</strong> <span>0974299896</span></p>
                <p><strong>Email:</strong> <span>abcdxyz2012003@gmail.com</span></p>
              </div>
              <div class="social-links d-flex mt-4">
                <a href=""><i class="bi bi-twitter-x"></i></a>
                <a href=""><i class="bi bi-facebook"></i></a>
                <a href=""><i class="bi bi-instagram"></i></a>
                <a href=""><i class="bi bi-linkedin"></i></a>
              </div>
            </div>
    
            <div class="col-lg-2 col-md-3 footer-links">
              <ul>
                <li><a href="#">Trang chủ</a></li>
                <li><a href="#">Về chúng tôi</a></li>
                <li><a href="#">Dịch vụ</a></li>
                <li><a href="#">Điều khoản</a></li>
                <li><a href="#">Chính sách</a></li>
              </ul>
            </div>
    
            <div class="col-lg-4 col-md-12 footer-newsletter">
              <p>Đăng ký để nhận những thông tin mới nhất về các cập nhật của chúng tôi</p>
              <form action="forms/newsletter.php" method="post" class="php-email-form">
                <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your subscription request has been sent. Thank you!</div>
              </form>
            </div>
    
          </div>
        </div>
    
        <div class="container copyright text-center mt-4">
          <p>© <span>Copyright</span> <strong class="px-1 sitename">MadCat</strong> <span>All Rights Reserved</span></p>
         
        </div>
    
      </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
