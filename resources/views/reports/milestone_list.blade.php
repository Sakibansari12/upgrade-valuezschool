<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Starter Page - OnePage Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

 



  <!-- milestone -->
  <link href="{{ asset('assets_milestone/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets_milestone/img/apple-touch-icon.png') }}" rel="apple-touch-icon">


  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets_milestone/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets_milestone/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets_milestone/vendor/aos/aos.css') }}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{ asset('assets_milestone/css/main.css') }}" rel="stylesheet">

  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>







</head>

<body class="starter-page-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.html" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 class="sitename">Valuez Explorer</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero">Valuez Logo<br></a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <!-- milestone Section -->
    <section id="milestone" class="milestone section">

      <div class="container" data-aos="fade-up">

        <div class="row gy-4">

          <div class="col-md-3 col-sm-6 col-xl-2 client-logo">
            <img src="{{ asset('assets_milestone/img/milestone/m_chart_1.png') }}" class="img-fluid" alt="">
            <span class="fw-bolder">Self-awarness</span>
          </div><!-- End Client Item -->

          <div class="col-md-3 col-sm-6 col-xl-2 client-logo">
            <img src="{{ asset('assets_milestone/img/milestone/m_chart_2.png') }}" class="img-fluid" alt="">
            <span class="fw-bolder">Self-awarness</span>
          </div><!-- End Client Item -->

          <div class="col-md-3 col-sm-6 col-xl-2 client-logo">
            <img src="{{ asset('assets_milestone/img/milestone/m_chart_3.png') }}" class="img-fluid" alt="">
            <span class="fw-bolder">Self-awarness</span>
          </div><!-- End Client Item -->

          <div class="col-md-3 col-sm-6 col-xl-2 client-logo">
            <img src="{{ asset('assets_milestone/img/milestone/m_chart_4.png') }} " class="img-fluid" alt="">
            <span class="fw-bolder">Self-awarness</span>
          </div><!-- End Client Item -->

          <div class="col-md-3 col-sm-6 col-xl-2 client-logo">
            <img src="{{ asset('assets_milestone/img/milestone/m_chart_5.png') }}" class="img-fluid" alt="">
            <span class="fw-bolder">Self-awarness</span>
          </div><!-- End Client Item -->

        </div>

      </div>

    </section><!-- /milestone Section -->

    <!-- Starter Section Section -->
    <section id="il-card" class="il-card section">

      <div class="container" data-aos="fade-up">

        <div class="parent">
          <div class="div2">
            <img src="assets/img/milestone/milestone.png" class="img-fluid" />
          </div>

          <div class="il-card-member" v-for="k in 34">
            <div class="member-img">
              <img src="assets/img/team/team-1.jpg" class="img-fluid" alt="">
            </div>
          </div>
        </div>



      </div>
      <div class="container-fluid">
        <div class="start-menu"> <img src="assets/img/milestone/start.png" class="img-fluid" /></div>
      </div>
    </section><!-- /Starter Section Section -->

  </main>

  <footer id="footer" class="footer">

    <div class="container-fluid copyright text-center">

    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
  <script>
    const { createApp, ref } = Vue
    createApp({
      setup() {
        const message = ref('Hello vue!')
        return {
          message
        }
      }
    }).mount('#il-card')
  </script>
</body>

</html>