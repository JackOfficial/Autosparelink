<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Happy Family</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet"> 

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

       <!-- Libraries Stylesheet -->
        <link href="{{ asset('frontend/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
        <link href="{{ asset('frontend/lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

        @livewireStyles
    </head>

    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar start -->
        <div class="container-fluid fixed-top px-0">
            <div class="container px-0">
                <div class="topbar">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-md-8">
                            <div class="topbar-info d-flex flex-wrap">
                                <a href="#" class="text-light me-4"><i class="fas fa-envelope text-white me-2"></i>{{ $organization->email }}</a>
                                <a href="tel:{{ $organization->phone }}" class="text-light"><i class="fas fa-phone-alt text-white me-2"></i>{{ $organization->phone }}</a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="topbar-icon d-flex align-items-center justify-content-end">
                                <a href="https://twitter.com/HFRwOrg" target="__blank" class="btn-square text-white me-2"><i class="fab fa-twitter"></i></a>
                                <a href="https://www.instagram.com/hf.r.o?igsh=OXVjbnlmOXVzNjQy" target="__blank" class="btn-square text-white me-2"><i class="fab fa-instagram"></i></a>
                                <a href="https://www.youtube.com/channel/UCbWRXU7KOSRxNodro3H8mug" target="__blank" class="btn-square text-white me-2"><i class="fab fa-youtube"></i></a>
                                <a href="#" target="__blank" class="btn-square text-white me-2"><i class="fab fa-facebook"></i></a>
                                <a href="https://www.linkedin.com/company/happy-family-rwanda" target="__blank" class="btn-square text-white me-0"><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <nav class="navbar navbar-light bg-light navbar-expand-xl">
                    <a href="/" class="navbar-brand ms-3">
                        <img src="{{ asset('storage/' . $organization->logo) }}" alt="{{ $organization->name }} logo" width="70px" height="auto" />
                    </a>
                    <button class="navbar-toggler py-2 px-3 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-primary"></span>
                    </button>
                    <div class="collapse navbar-collapse bg-light" id="navbarCollapse">
                        <div class="navbar-nav ms-auto">
                            <a href="/" class="nav-item nav-link active">Home</a>
                            <a href="#" class="nav-item nav-link">About</a>
                            <a href="#" class="nav-item nav-link">Causes</a>
                            <a href="#" class="nav-item nav-link">Projects</a>
                            <a href="#" class="nav-item nav-link">Events</a>
                            <a href="#" class="nav-item nav-link">News</a>
                            <a href="#" class="nav-item nav-link">Contact</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">More</a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                    <a href="#" class="dropdown-item">Volunteers</a>
                                    <a href="#" class="dropdown-item">Career</a>
                                    <a href="#" class="nav-item nav-link">Gallery</a>
                                    <a href="#" class="dropdown-item">Donation</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center flex-nowrap pt-xl-0" style="margin-left: 15px;">
                            <a href="#" class="btn-hover-bg btn btn-primary text-white py-2 px-4 me-3">Donate Now</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->

         @yield('content')

        <!-- Footer Start -->
        <div class="container-fluid footer bg-dark text-body py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-6 col-xl-5">
                        <div class="footer-item">
                            <h4 class="mb-4 text-white">Stay Connected</h4>
                            <p class="mb-4">
                                Join the Happy Family community to receive the latest updates, and inspiring stories straight to your inbox. Be the first to know about upcoming events and various opportunities. 
                            </p>
                            <livewire:subscribe-component />
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-2">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">Quick Links</h4>
                            <a href="/about"><i class="fas fa-angle-right me-2"></i> About</a>
                            <a href="/causes"><i class="fas fa-angle-right me-2"></i> Causes</a>
                            <a href="/blogs"><i class="fas fa-angle-right me-2"></i> News</a>
                            <a href="/stories"><i class="fas fa-angle-right me-2"></i> Stories</a>
                            <a href="/contact"><i class="fas fa-angle-right me-2"></i> Contact</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-2">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">Other Links</h4>
                            <a href="/gallery"><i class="fas fa-angle-right me-2"></i> Gallery</a>
                            <a href="/events"><i class="fas fa-angle-right me-2"></i> Events</a>
                            <a href="/projects"><i class="fas fa-angle-right me-2"></i> Projects</a>
                            <a href="/volunteer"><i class="fas fa-angle-right me-2"></i> Volunteers</a>
                            <a href="/donate"><i class="fas fa-angle-right me-2"></i> Donate</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item">
                            <h4 class="mb-4 text-white">Our Gallery</h4>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="{{ asset('frontend/img/gallery-footer-1.jpg') }}" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="{{ asset('frontend/img/gallery-footer-1.jpg') }}" data-lightbox="footerGallery-1" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                               <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="{{ asset('frontend/img/gallery-footer-2.jpg') }}" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="{{ asset('frontend/img/gallery-footer-2.jpg') }}" data-lightbox="footerGallery-2" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="{{ asset('frontend/img/gallery-footer-3.jpg') }}" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="{{ asset('frontend/img/gallery-footer-3.jpg') }}" data-lightbox="footerGallery-3" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="{{ asset('frontend/img/gallery-footer-4.jpg') }}" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="{{ asset('frontend/img/gallery-footer-4.jpg') }}" data-lightbox="footerGallery-4" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="{{ asset('frontend/img/gallery-footer-5.jpg') }}" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="{{ asset('frontend/img/gallery-footer-5.jpg') }}" data-lightbox="footerGallery-5" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                               <div class="col-4">
									<div class="footer-gallery">
										<img src="{{ asset('frontend/img/gallery-footer-6.jpg') }}" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="{{ asset('frontend/img/gallery-footer-6.jpg') }}" data-lightbox="footerGallery-6" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Copyright Start -->
        <div class="container-fluid copyright py-4">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-md-4 text-center text-md-start mb-md-0">
                        <span class="text-body"><a href="#"><i class="fas fa-copyright text-light me-2"></i>{{ $organization->name }}</a>, All right reserved.</span>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="#" target="__blank" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="https://twitter.com/HFRwOrg" target="__blank" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-twitter"></i></a>
                            <a href="https://www.instagram.com/hf.r.o?igsh=OXVjbnlmOXVzNjQy" target="__blank" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-instagram"></i></a>
                            <a href="https://www.youtube.com/channel/UCbWRXU7KOSRxNodro3H8mug" target="__blank" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-youtube"></i></a>
                            <a href="https://www.linkedin.com/company/happy-family-rwanda" target="__blank" class="btn-hover-color btn-square text-white me-0"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center text-md-end text-body">
                        <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                        <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                        <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                        Designed By <a class="border-bottom" href="https://htmlcodex.com">Tonny Jack</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ asset('frontend/lib/easing/easing.min.js') }}"></script>
        <script src="{{ asset('frontend/lib/waypoints/waypoints.min.js') }}"></script>
        <script src="{{ asset('frontend/lib/counterup/counterup.min.js') }}"></script>
        <script src="{{ asset('frontend/lib/owlcarousel/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('frontend/lib/lightbox/js/lightbox.min.js') }}"></script>
        

        <!-- Template Javascript -->
        <script src="{{ asset('frontend/js/main.js') }}"></script>
        @livewireScripts
    </body>

</html>