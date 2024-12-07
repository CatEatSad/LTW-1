
<?php
// Add at top of index.php before session_start()
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Index - Presento Bootstrap Template</title>
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

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.php" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">MadCat</h1>
        <span>.</span>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Trang chủ<br></a></li>
          <li><a href="#about">Về chúng tôi</a></li>
          <li><a href="#services">Dịch vụ</a></li>
          <li><a href="#contact">Liên hệ</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="btn-getstarted" href="register.php">Đăng ký</a>
      <a class="btn-getstarted" href="<?php echo isset($_SESSION['loggedin']) && $_SESSION['loggedin'] ? 'dashboard.php' : 'login.php'; ?>">
  Đăng Nhập
      </a>

    </div>
  </header>

  <main class="main">


    <section id="hero" class="hero section">

      <img src="assets/img/hero-bg.jpg" alt="" data-aos="fade-in">

      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <h2 data-aos="fade-up" data-aos-delay="100">Trải nghiệm việc quản lý doanh nghiệp của bạn với MadCat</h2>
            <p data-aos="fade-up" data-aos-delay="200"></p>
            <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
              <a href="#about" class="btn-get-started">ĐĂNG KÝ NGAY</a>
              <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox btn-watch-video d-flex align-items-center"><i class="bi bi-play-circle"></i><span>Video giới thiệu</span></a>
            </div>

          </div>
        </div>
      </div>

    </section>

    <section id="about" class="about section section-bg dark-background">

      <div class="container position-relative">

        <div class="row gy-5">

          <div class="content col-xl-5 d-flex flex-column" data-aos="fade-up" data-aos-delay="100">
            <h3>Hãy để chúng tôi giới thiệu về bản thân mình</h3>
            <p>
              Với 5 năm làm việc trong lĩnh vực quản lý hỗ trợ các doanh nghiệp quản lý, chúng tôi cam kết đưa tới bạn chất lượng tốt nhất và phù hợp nhất với doanh nghiệp của bạn.
            </p>
            <a href="#" class="about-btn align-self-center align-self-xl-start"><span>About us</span> <i class="bi bi-chevron-right"></i></a>
          </div>

          <div class="col-xl-7" data-aos="fade-up" data-aos-delay="200">
            <div class="row gy-4">

              <div class="col-md-6 icon-box position-relative">
                <i class="bi bi-briefcase"></i>
                <h4><a href="" class="stretched-link">Quản lý doanh nghiệp</a></h4>
                <p>Chúng tôi có đội ngũ nhân viên đã làm việc chuyên nghiệp và hiệu quả với nhiều năm kinh nghiệm </p>
              </div>

              <div class="col-md-6 icon-box position-relative">
                <i class="bi bi-box2"></i>
                <h4><a href="" class="stretched-link">Khách hàng</a></h4>
                <p>Đã có hơn 200 nhà cung cấp thuộc đủ mọi lĩnh vực cả trong và ngoài nước đã là khách hàng của chúng tôi</p>
              </div>

              <div class="col-md-6 icon-box position-relative">
                <i class="bi bi-broadcast"></i>
                <h4><a href="" class="stretched-link">Liên tục hỗ trợ</a></h4>
                <p>Hỗ trợ liên tục 24/7 nếu doanh nghiệp bạn gặp sự cố hoặc có thắc mắc gì liên quan đến hệ thống của chúng tôi</p>
              </div>

              <div class="col-md-6 icon-box position-relative">
                <i class="bi bi-server"></i>
                <h4><a href="" class="stretched-link">Kho lưu trữ rộng lớn</a></h4>
                <p>Giúp bạn và doanh nghiệp tối ưu trong việc quản lý thông tin cá nhân , khách hàng và quản lý sản phẩm</p>
              </div>

            </div>
          </div>

        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Services Section -->
    <section id="services" class="services section section-bg dark-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Dịch vụ</h2>
        <p>Đến với chúng tôi bạn sẽ nhận được các dịch vụ sau </p>
      </div>

      <div class="container">

        <div class="row gy-4">

          <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item d-flex position-relative h-100">
              <i class="bi bi-briefcase icon flex-shrink-0"></i>
              <div>
                <h4 class="title"><a href="service-details.html" class="stretched-link">Quản lý doanh nghiệp tự động</a></h4>
                <p class="description">Cung cấp các dịch vụ quản lý tự động liên quan đến doanh nghiệp của bạn</p>
              </div>
            </div>
          </div>

          <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item d-flex position-relative h-100">
              <i class="bi bi-card-checklist icon flex-shrink-0"></i>
              <div>
                <h4 class="title"><a href="service-details.html" class="stretched-link">Quản lý các việc</a></h4>
                <p class="description">Giúp việc quản lý nhân viên và công việc của nhân viên 1 các dễ dàng đảm bảo hiệu quả lớn trong công việc</p>
              </div>
            </div>
          </div>

          <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item d-flex position-relative h-100">
              <i class="bi bi-bar-chart icon flex-shrink-0"></i>
              <div>
                <h4 class="title"><a href="service-details.html" class="stretched-link">Thống kê biểu đồ mua bán</a></h4>
                <p class="description">Có cái nhìn tổng quan nhất về thị trường hiện tại và nhu cầu mua bán của các doanh nghiệp đối với bạn</p>
              </div>
            </div>
          </div>

          <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item d-flex position-relative h-100">
              <i class="bi bi-mailbox icon flex-shrink-0"></i>
              <div>
                <h4 class="title"><a href="service-details.html" class="stretched-link">Nhận tin nhắn từ các đối tác</a></h4>
                <p class="description">Quản lý các thư mời của đối tác nhanh chóng sắp xếp cho các buổi hẹn và họp mặt</p>
              </div>
            </div>
          </div>

          <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="service-item d-flex position-relative h-100">
              <i class="bi bi-brightness-high icon flex-shrink-0"></i>
              <div>
                <h4 class="title"><a href="service-details.html" class="stretched-link">Giao diện đơn giản dễ sử dụng</a></h4>
                <p class="description">Với giao diện dễ dàng và tiện lợi giúp cho không mất quá nhiều thời gian trong việc đào tạo lại nhân viên , thuận tiện cho việc sử dụng lâu dài</p>
              </div>
            </div>
          </div>

          <div class="col-md-6" data-aos="fade-up" data-aos-delay="600">
            <div class="service-item d-flex position-relative h-100">
              <i class="bi bi-calendar4-week icon flex-shrink-0"></i>
              <div>
                <h4 class="title"><a href="service-details.html" class="stretched-link">Quản lý lịch trình gặp gỡ đối tác</a></h4>
                <p class="description">Lịch trình được sắp xếp theo mong muốn và nhu cầu giúp các công việc của nhân viên hay các cuộc gặp mặt của đối tác trở nên dễ dàng</p>
              </div>
            </div>
          </div>

        </div>

      </div>

    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="pricing section section-bg dark-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Giá thành</h2>
        <p>Chúng tôi có nhiều mức giá phù hợp với quy mô doanh nghiệp của bạn đảm bảo việc kinh doanh của bạn luôn thuận lợi</p>
      </div>

      <div class="container">

        <div class="row g-4 g-lg-0">

          <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="100">
            <div class="pricing-item">
              <h3>Gói gia đình</h3>
              <h4><sup>$</sup>5000000<span> / tháng</span></h4>
              <ul>
                <li><i class="bi bi-check"></i> <span>Hỗ trợ trực tuyến 24/7</span></li>
                <li><i class="bi bi-check"></i> <span>Có các chức năng quản lý đơn giản</span></li>
                <li><i class="bi bi-check"></i> <span>Dữ liệu lưu trữ ở mức nhỏ</span></li>
                <li class="na"><i class="bi bi-x"></i> <span>Chức năng quản lý nhân viên</span></li>
                <li class="na"><i class="bi bi-x"></i> <span>Một số chức năng khác</span></li>
              </ul>
              <div class="text-center"><a href="#" class="buy-btn">Sử dụng ngay</a></div>
            </div>
          </div>

          <div class="col-lg-4 " data-aos="zoom-in" data-aos-delay="200">
            <div class="pricing-item">
              <h3>Gói doanh nghiệp nhỏ</h3>
              <h4><sup>$</sup>20000000<span> / tháng</span></h4>
              <ul>
                <li><i class="bi bi-check"></i> <span>Hỗ trợ trực tuyến 24/7</span></li>
                <li><i class="bi bi-check"></i> <span>Đầy đủ các chức năng cơ bản</span></li>
                <li><i class="bi bi-check"></i> <span>Hỗ trợ lưu trữ ở mức trung</span></li>
                <li><i class="bi bi-check"></i> <span>Đầy đủ các chức năng nâng cao </span></li>
                <li><i class="bi bi-check"></i> <span>Đảm bảo truy cập lớn giúp khách hàng và người sử dụng</span></li>
              </ul>
              <div class="text-center"><a href="#" class="buy-btn">Sử dụng ngay</a></div>
            </div>
          </div>

          <div class="col-lg-4" data-aos="zoom-in" data-aos-delay="100">
            <div class="pricing-item">
              <h3>Gói tập đoàn</h4>
              <h4><sup>$</sup>50000000<span> / tháng</span></h4>
              <ul>
                <li><i class="bi bi-check"></i> <span>Hỗ trợ trực tuyến 24/7</span></li>
                <li><i class="bi bi-check"></i> <span>Đầy đủ các chức năng cơ bản</span></li>
                <li><i class="bi bi-check"></i> <span>Hỗ trợ lưu trữ ở mức lớn </span></li>
                <li><i class="bi bi-check"></i> <span>Đảm bảo đầy đủ các chức năng nâng cao</span></li>
                <li><i class="bi bi-check"></i> <span>Hệ thống lớn giúp cho việc sử dụng của bạn có thể chịu tải lớn</span></li>
              </ul>
              <div class="text-center"><a href="#" class="buy-btn">Sử dụng ngay</a></div>
            </div>
          </div>

        </div>

      </div>

    </section><!-- /Pricing Section -->

    <!-- Faq Section -->
    <section id="faq" class="faq section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Các câu hỏi thường gặp khi sử dụng </h2>
        <p>Giúp bạn có thể nhanh chóng </p>
      </div>

      <div class="container">

        <div class="row justify-content-center">

          <div class="col-lg-10" data-aos="fade-up" data-aos-delay="100">

            <div class="faq-container">

              <div class="faq-item faq-active">
                <h3>Gia đình tôi làm cung cấp cho các cửa hàng quán quanh đây thì nên sử dụng gói nào?</h3>
                <div class="faq-content">
                  <p>Theo công ty thì gói gia đình là hợp lý nhát ạ, nếu sau này thành doanh nghiệp lớn mình có thể nâng cấp gói cao hơn ạ. Chúc gia đình mình thành công ạ .</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>

              <div class="faq-item">
                <h3>Bên mình có đào tạo nhân viên doanh nghiệp sử dụng không?</h3>
                <div class="faq-content">
                  <p>Công ty MadCat sẽ hỗ trợ đào tạo nhân viên của công ty hoặc doanh nghiệp ạ. Tuỳ vào nhu cầu và gói bên công ty sẽ hỗ trợ phí đào tạo ạ.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>

              <div class="faq-item">
                <h3>Nếu sử dụng gói gia đình thì sẽ bao gồm những gì?</h3>
                <div class="faq-content">
                  <p>Trong gói gia đình sẽ bao gồm các chức năng cơ bản như hiển thị doanh thu , biểu đồ , quản lý sản phẩm.</p>
                </div>
                <i class="faq-toggle bi bi-chevron-right"></i>
              </div>
            </div>

          </div>

        </div>

      </div>

    </section>

    <section id="contact" class="contact section">


      <div class="container section-title" data-aos="fade-up">
        <h2>Liên hệ</h2>
        <p>Nếu có bất kì điều gì thắc mắc hãy liên hệ với chúng tôi </p>
      </div>

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-6 ">
            <div class="row gy-4">

              <div class="col-lg-12">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                  <i class="bi bi-geo-alt"></i>
                  <h3>Địa chỉ</h3>
                  <p>96A Đường Trần Phú, P. Mộ Lao, Hà Đông, Hà Nội</p>
                </div>
              </div>

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
                  <i class="bi bi-telephone"></i>
                  <h3>Số điện thoại</h3>
                  <p>0974299896</p>
                </div>
              </div>

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
                  <i class="bi bi-envelope"></i>
                  <h3>Email</h3>
                  <p>abcdxyz2012003@gmail.com</p>
                </div>
              </div>

            </div>
          </div>

          <div class="col-lg-6">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="500">
              <div class="row gy-4">
                <div class="col-md-6">
                  <input type="text" name="name" class="form-control" placeholder="Họ và tên" required="">
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="Email" required="">
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="subject" placeholder="Tiêu đề" required="">
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="4" placeholder="Tin nhắn" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Loading</div>
                  <div class="error-message"></div>
                  <div class="sent-message">Your message has been sent. Thank you!</div>

                  <button type="submit">Gửi tin nhắn</button>
                </div>

              </div>
            </form>
          </div>

        </div>

      </div>

    </section>

  </main>

  <footer id="footer" class="footer dark-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.php" class="logo d-flex align-items-center"></a>
            <span class="sitename">MadCat</span>
          </a>
          <div class="footer-contact pt-3"></div>
            <p>96A Đường Trần Phú </p>
            <p>P. Mộ Lao, Hà Đông, Hà Nội</p>
            <p class="mt-3"><strong>Phone:</strong> <span>0974299896</span></p>
            <p><strong>Email:</strong> <span>abcdxyz2012003@gmail.com</span></p>
          </div>
          <div class="social-links d-flex mt-4"></div>
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

        <div class="col-lg-4 col-md-12 footer-newsletter"></div>
          <p>Đăng ký để nhận những thông tin mới nhất về các cập nhật của chúng tôi</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form"></form>
            <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Subscribe"></div>
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your subscription request has been sent. Thank you!</div>
          </form>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4"></div>
      <p>© <span>Copyright</span> <strong class="px-1 sitename">MadCat</strong> <span>All Rights Reserved</span></p>
     
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>