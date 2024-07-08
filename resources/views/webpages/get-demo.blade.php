<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Karla" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/getdemo/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/getdemo/css/owl.theme.default.min.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mystery+Quest&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/getdemo/css/style.css') }}">
    <title>Valuez | LMS</title>
</head>

<body>
    <nav class="navbar info-text">
        Based on NEP 2020
    </nav>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid mx-5">
            <a href="#" class="navbar-brand">
                <img src="{{ asset('assets/getdemo/Image/logo.webp') }}" width="100" height="100" alt="Valuezhut.com">
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ">


                </div>
                <div class="navbar-nav ms-auto top-nav-btn" id="navbarCollapse">
                    <span>Home</span>
                    <a href="#" class="active">Teacher Login</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container-fluid">

        <div class="background">
            <img src="{{ asset('assets/getdemo/Image/vALUEZ LANDING PAGE.png') }}" class="img-fluid" alt="Responsive image">
            <div class="top-left">
                <h3>Classroom Modules by Valuez</h3>
                <p>Our friendly teacher's portal gives access to classroom modules in "Life Skills & Values" as well as
                    "Near
                    future technologies". Modules are endorsed by <b>120+ schools </b>and growing!</p>
            </div>
        </div>



        <div class="row">
            <div class="col">
                <h4 class="mt-3">Near Future Technologies + Values Education Package <br> for 21st Century School
                </h4>
            </div>
            <div class="w-100"></div>
            <div class="col text-center">
                <div class="video-container">
                    <iframe
                        src="https://player.vimeo.com/video/724922811?color&autopause=0&loop=0&muted=0&title=1&portrait=1&byline=1&h=9028df6869#t="
                        frameborder="0" allowfullscreen></iframe>
                    </iframe>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div id="sider1" class="slider">
                    <h2>Valuez Modules</h2>
                    <div class="owl-carousel owl-carousel-one owl-theme">
                        <div class="item">
                            <img src="{{ asset('assets/getdemo/images2/She - Power.png') }}" alt="">
                        </div>
                        <div class="item">
                            <img src="{{ asset('assets/getdemo/images2/Respect all Work.png') }}" alt="">
                        </div>
                        <div class="item">
                            <img src="{{ asset('assets/getdemo/images2/Let\'s talk about Body Boundaries.png') }}" alt="">
                        </div>
                        <div class="item">
                            <img src="{{ asset('assets/getdemo/images2/Keep your cool through exams.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">

                <div class="contact-wrap w-100 p-4">

                    <div id="form-message-warning" class="mb-4"></div>
                    <div id="form-message-success" class="mb-4">
                        Your message was sent, thank you!
                    </div>
                    <form method="POST" id="contactForm" name="contactForm" class="contactForm">
                        <div class="row">
                            <h5>Get In Touch</h5>
                            <div class="col-md-12 margin-bottom">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Name">
                                </div>
                            </div>
                            <div class="col-md-12 margin-bottom">
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Email Id">
                                </div>
                            </div>
                            <div class="col-md-12 margin-bottom">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject" id="subject"
                                        placeholder="Phone Number">
                                </div>
                            </div>
                            <div class="col-md-12 margin-bottom">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject" id="subject"
                                        placeholder="Name Of the School/Institution">
                                </div>
                            </div>
                            <div class="col-md-12 margin-bottom">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject" id="subject"
                                        placeholder="Role">
                                </div>
                            </div>
                            <div class="col-md-12 margin-bottom">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject" id="subject"
                                        placeholder="City">
                                </div>
                            </div>
                            <p>Already a subscriber?<a href="#">Login here</a></p>
                            <input type="submit" value="Send Message" class="btn btn-primary">

                    </form>
                </div>
            </div>
        </div>


    </div>




    <div class="row">

        <div class="col-md-8">
            <div id="sider2" class="slider">

                <h2>Techies With Ethics</h2>
                <div class="owl-carousel owl-carousel-two owl-theme">
                    <div class="item">
                        <img src="{{ asset('assets/getdemo/images2/NANOTECHNOLOGY.png') }}" alt="">
                    </div>
                    <div class="item">
                        <img src="{{ asset('assets/getdemo/images2/DRONE.png') }}" alt="">
                    </div>
                    <div class="item">
                        <img src="{{ asset('assets/getdemo/images2/METAVERSE.png') }}" alt="">
                    </div>
                    <div class="item">
                        <img src="{{ asset('assets/getdemo/images2/MICROPLASTICS.png') }}" alt="">
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-4">

        </div>


    </div>
    <div class="row">
        <div class="col-md-8 ">

            <div id="sider3" class="slider">
                <h2>Future Ready Bulletins</h2>
                <div class="owl-carousel owl-carousel-three owl-theme">
                    <div class="item">
                        <img src="{{ asset('assets/getdemo/images2/08.jpg') }}" alt="">
                    </div>
                    <div class="item">
                        <img src="{{ asset('assets/getdemo/images2/11.jpg') }}" alt="">
                    </div>
                    <div class="item">
                        <img src="{{ asset('assets/getdemo/images2/12.jpg') }}" alt="">
                    </div>
                    <div class="item">
                        <img src="{{ asset('assets/getdemo/images2/13.jpg') }}" alt="">
                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>


    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <p>© 2023 Valuez School. All Rights Reserved.</p>
                </div>
                <div class="col-md-6">
                    <P><a href="#">Privacy Policy</a> | <a href="#">Disclaimer</a></P>
                </div>
            </div>

        </div>
    </footer>
    <script src="{{ asset('assets/getdemo/script/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/getdemo/script/owl.carousel.js') }}"></script>
    <script src="{{ asset('assets/getdemo/script/script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
