@extends('layout.main')
@section('content')
    <!-- Main content -->
<!-- start  -->
<section class="content">
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header header_milestone">
          <div class="container-fluid container-xl position-relative d-flex align-items-center">
            <div class="logo d-flex align-items-center me-auto">
              <h1 class="sitename">Valuez Explorer</h1>
            </div>
            <!-- <nav id="navmenu" class="navmenu">
              <ul>
                <li><a href="#hero">Valuez Logo<br></a></li>
              </ul>
              <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav> -->

            <div class="float-end">
              <h1 class="sitename">Valuez Logo</h1>
            </div>
            
          </div>

          <div class="card-actions float-end">
            <div class="dropdown show">

            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <main class="main-milestone">

              <section id="milestone" class="milestone section-milestone">

                <div class="container" data-aos="fade-up">
                  <div class="row section-milestone-row- gy-4">

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
              </section>
              <section id="il-card" class="il-card section-milestone">
                <div class="container" data-aos="fade-up">

                  <div class="parent-milestone">
                    <div class="div2">
                        <img src="{{ asset('assets_milestone/img/milestone/milestone.png') }}" class="img-fluid" />
                    </div>

                   
                    @foreach ($data_lesson_plan as $key => $data)
                    <h1>{{ $data['id'] }}</h1>
                    <!-- <h1>{{ $data['title'] }}</h1> -->
                        <div class="il-card-member" v-for="k in 34">
                            <div class="member-img">
                                <img src="{{ 'https://learn.valuezschool.com/uploads/lessonplan/' . ($data['lesson_image'] ? $data['lesson_image'] : 'no_image.png') }}" class="img-fluid" alt="">
                            </div>
                        </div>
                    @endforeach


                  </div>
                </div>
                <div class="container-fluid">
                  <div class="start-menu"> <img src="{{ asset('assets_milestone/img/milestone/start.png') }}"
                      class="img-fluid" /></div>
                </div>
              </section>
            </main>
            
          </div>
        </div>
        <footer class="footer-milestone">
              <div class="container-fluid copyright text-center" style="
                          background-color: #00892c;
                          color: #f6fafd;
                          margin-top: 70px;
                          background-image: url({{ asset('assets_milestone/img/bg-footer.jpg') }});
                          background-position: center center;
                          background-repeat: repeat;
                          background-size: contain;
                          padding: 35px;
                          ">
                <!-- Add any additional content you want inside this div -->
              </div>
            </footer>
        
      </div>

    </div>
  </div>
</section>
<!-- end -->
@endsection



