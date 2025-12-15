<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="AutoSpare Parts E-commerce" name="keywords">
    <meta content="Buy original auto parts, accessories, and tools online" name="description">

    <!-- Favicon -->
    <link href="{{ asset('frontend/img/logo.png') }}" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('frontend/lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">

    @yield('style')

    <style>
        /* Navbar Custom Styles */
        .navbar {
            padding: 1rem 2rem;
        }

        .navbar-brand img {
            height: 60px;
        }

        .nav-item.nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease;
        }

        .nav-item.nav-link:hover {
            color: #0d6efd;
        }

        .btn-pill {
            border-radius: 50px;
            padding: 0.5rem 1.8rem;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .navbar-icons {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .navbar-icons a {
            position: relative;
            font-size: 1.25rem;
            color: #fff;
            transition: color 0.3s ease;
        }

        .navbar-icons a:hover {
            color: #0d6efd;
        }

        .navbar-icons .badge {
            position: absolute;
            top: -6px;
            right: -10px;
            font-size: 0.65rem;
            padding: 0.25rem 0.45rem;
        }

        /* Increase space between logo and menu links */
        .navbar-nav {
            margin-left: 40px;
        }

        /* Enhanced Dropdown Styles */
        .dropdown-menu a.dropdown-item {
            transition: all 0.2s ease-in-out;
            font-weight: 500;
        }

        .dropdown-menu a.dropdown-item:hover {
            background-color: #f1f5f9;
            color: #0d6efd;
            transform: translateX(5px);
        }

        .dropdown-menu i {
            font-size: 1rem;
        }

        .btn-pill.dropdown-toggle {
            border-radius: 50px;
            padding: 0.5rem 1.5rem;
        }
    </style>
</head>

<body>
    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top py-2">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand mr-5" href="/">
                <img src="{{ asset('frontend/img/logo.png') }}" alt="Logo">
            </a>

            <!-- Toggler for Mobile -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Items -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav mr-auto ml-4">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="/shop/products">Shop</a></li>

                    <!-- Mega Menu for Categories -->
                    <li class="nav-item dropdown position-static">
                        <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-toggle="dropdown">
                            Genuine Catalogs
                        </a>
                        <livewire:categories-component />
                    </li>

                   <li class="nav-item"><a class="nav-link" href="/brands">Brands</a></li>

                    <!-- Resources Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="resourcesDropdown" role="button" data-toggle="dropdown">
                            Resources
                        </a>
                        <div class="dropdown-menu shadow-sm" aria-labelledby="resourcesDropdown">
                            <a class="dropdown-item d-flex align-items-center" href="/blogs">
                                <i class="fas fa-newspaper mr-2 text-primary"></i> Blogs
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="/articles">
                                <i class="fas fa-file-alt mr-2 text-primary"></i> Articles
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="/news">
                                <i class="fas fa-bullhorn mr-2 text-primary"></i> News
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item d-flex align-items-center" href="/terms-and-conditions">
                                <i class="fas fa-file-contract mr-2 text-primary"></i> Terms & Conditions
                            </a>
                            <a class="dropdown-item d-flex align-items-center" href="/policies">
                                <i class="fas fa-shield-alt mr-2 text-primary"></i> Policies
                            </a>
                        </div>
                    </li>
                </ul>

                <!-- Right Side Icons and Login/User Info -->
                <div class="ml-auto d-flex align-items-center">
                    <!-- Wishlist Icon -->
                    <a href="#" class="text-white mr-3 position-relative" title="Wishlist">
                        <i class="fas fa-heart fa-lg"></i>
                        <span class="badge badge-primary position-absolute" style="top:-5px; right:-10px;">0</span>
                    </a>

                    <!-- Cart Icon -->
                    <a href="#" class="text-white mr-3 position-relative" title="Cart">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <span class="badge badge-primary position-absolute" style="top:-5px; right:-10px;">0</span>
                    </a>

                    <!-- Authentication Links -->
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-primary btn-pill ml-2">Login / Register</a>
                    @else
                        <div class="dropdown">
                            <a href="#" class="btn btn-outline-primary btn-pill dropdown-toggle d-flex align-items-center" id="userDropdown" data-toggle="dropdown">
                                <i class="fas fa-user-circle fa-lg mr-2"></i> {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow-sm p-0" aria-labelledby="userDropdown" style="min-width: 200px; border-radius: 12px; overflow: hidden;">
                                <a class="dropdown-item d-flex align-items-center py-2 px-3" href="">
                                    <i class="fas fa-user mr-2 text-primary"></i> Profile
                                </a>
                                <div class="dropdown-divider m-0"></div>
                                <a class="dropdown-item d-flex align-items-center py-2 px-3" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt mr-2 text-danger"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-secondary mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <h5 class="text-secondary text-uppercase mb-4">Get In Touch</h5>
                <p>Need help? Contact us or visit our store to find the original auto parts you need.</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>123 AutoStreet, Kigali, Rwanda</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>support@autosparelink.com</p>
                <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+250 788 430 122</p>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <!-- Quick Links -->
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Quick Links</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-secondary mb-2" href="/"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            <a class="text-secondary mb-2" href="/shop"><i class="fa fa-angle-right mr-2"></i>Shop</a>
                            <a class="text-secondary mb-2" href="/about"><i class="fa fa-angle-right mr-2"></i>About Us</a>
                            <a class="text-secondary mb-2" href="/contact"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                            <a class="text-secondary mb-2" href="/blogs"><i class="fa fa-angle-right mr-2"></i>Blogs</a>
                            <a class="text-secondary mb-2" href="/articles"><i class="fa fa-angle-right mr-2"></i>Articles</a>
                            <a class="text-secondary mb-2" href="/news"><i class="fa fa-angle-right mr-2"></i>News</a>
                            <a class="text-secondary mb-2" href="/terms-and-conditions"><i class="fa fa-angle-right mr-2"></i>Terms & Conditions</a>
                            <a class="text-secondary mb-2" href="/policies"><i class="fa fa-angle-right mr-2"></i>Policies</a>
                        </div>
                    </div>

                    <!-- Popular Brands -->
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Popular Brands</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-secondary mb-2" href="#">Bosch</a>
                            <a class="text-secondary mb-2" href="#">Continental</a>
                            <a class="text-secondary mb-2" href="#">Toyota</a>
                            <a class="text-secondary mb-2" href="#">Mercedes</a>
                            <a class="text-secondary mb-2" href="#">BMW</a>
                        </div>
                    </div>

                    <!-- Newsletter -->
                    <div class="col-md-4 mb-5">
                        <h5 class="text-secondary text-uppercase mb-4">Newsletter</h5>
                        <p>Subscribe for the latest offers and updates.</p>
                        <livewire:subscribe-component />
                        <h6 class="text-secondary text-uppercase mt-4 mb-3">Follow Us</h6>
                        <div class="d-flex">
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-primary btn-square mr-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a class="btn btn-primary btn-square" href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
            <div class="col-md-6 px-xl-0 text-center text-md-left mb-2 mb-md-0">
                <p class="mb-0 text-secondary">&copy; <a class="text-primary" href="#">AutoSpareLink</a>. All Rights Reserved.</p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="{{ asset('frontend/img/payments.png') }}" alt="Payments">
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('frontend/lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('frontend/lib/owlcarousel/owl.carousel.min.js') }}"></script>

    <!-- Contact Javascript File -->
    <script src="{{ asset('frontend/mail/jqBootstrapValidation.min.js') }}"></script>
    <script src="{{ asset('frontend/mail/contact.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('frontend/js/main.js') }}"></script>
</body>

</html>
