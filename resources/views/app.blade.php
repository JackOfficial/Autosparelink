<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="description" content="Happy Family">
    
    <meta name="author" content="Happy Family Rwanda">
  
  <!-- Mobile Specific Meta
    ================================================== -->
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('Front/images/hfro logo.png') }}" />
    
    <!-- CSS
    ================================================== -->
    <!-- Themefisher Icon font -->
    <link rel="stylesheet" href="{{ asset('Front/plugins/themefisher-font/style.css') }}">
    <!-- bootstrap.min css -->
    <link rel="stylesheet" href="{{ asset('Front/plugins/bootstrap/css/bootstrap.min.css') }}">
    <!-- Lightbox.min css -->
    <link rel="stylesheet" href="{{ asset('Front/plugins/lightbox2/dist/css/lightbox.min.css') }}">
    <!-- animation css -->
    <link rel="stylesheet" href="{{ asset('Front/plugins/animate/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('Back/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Slick Carousel -->
    <link rel="stylesheet" href="{{ asset('Front/plugins/slick/slick.css') }}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{ asset('Front/css/style.css') }}"> 
     <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('Back/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
     <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('Back/plugins/toastr/toastr.min.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
  </head>
  <body id="body">

    @inertia

        <!-- 
    Essential Scripts
    =====================================-->
    <!-- Main jQuery -->
    <script src="{{ asset('Front/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Google Map -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBu5nZKbeK-WHQ70oqOWo-_4VmwOwKP9YQ"></script>
    <script  src="{{ asset('Front/plugins/google-map/gmap.js') }}"></script>

    <!-- Form Validation -->
    <script src="{{ asset('Front/plugins/form-validation/jquery.form.js') }}"></script> 
    <script src="{{ asset('Front/plugins/form-validation/jquery.validate.min.js') }}"></script>
    
    <!-- Bootstrap4 -->
    <script src="{{ asset('Front/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Parallax -->
    <script src="{{ asset('Front/plugins/parallax/jquery.parallax-1.1.3.js') }}"></script>
    <!-- lightbox -->
    <script src="{{ asset('Front/plugins/lightbox2/dist/js/lightbox.min.js') }}"></script>
    <!-- Owl Carousel -->
    <script src="{{ asset('Front/plugins/slick/slick.min.js') }}"></script>
    <!-- filter -->
    <script src="{{ asset('Front/plugins/filterizr/jquery.filterizr.min.js') }}"></script>
    <!-- Smooth Scroll js -->
    <script src="{{ asset('Front/plugins/smooth-scroll/smooth-scroll.min.js') }}"></script>
    {{-- <script src="https://cdn.ckbox.io/ckbox/latest/ckbox.js"></script> --}}
    <!-- Custom js -->
    <script src="{{ asset('Front/js/script.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  </body>
</html>