<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'AutoSpareLink Admin')</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
<!-- Font Awesome 6 Free via CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-wx3ZPVD6pK+... (truncated) ..." crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('back/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('back/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('back/plugins/jqvmap/jqvmap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('back/dist/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('back/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('back/plugins/daterangepicker/daterangepicker.css') }}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('back/plugins/summernote/summernote-bs4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('back/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css') }}">
 <!-- DataTables -->
 <link rel="stylesheet" href="{{ asset('back/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{ asset('back/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
 <link rel="stylesheet" href="{{ asset('back/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
 <link rel="icon" type="image/png" href="{{ asset('frontend/img/logo.png') }}">
 @stack('styles')
@livewireStyles
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset('frontend/img/logo.png') }}" alt="AdminLTELogo" height="60" width="60">
  </div>

  <!-- Navbar -->
   <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/admin" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="/Back/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="/Back/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="/Back/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

<aside class="main-sidebar sidebar-dark-pink elevation-4" 
       x-data="{ 
          search: '',
          {{-- This logic checks if the item or any of its children contains the search text --}}
          isVisible(el) {
            if (this.search === '') return true;
            return el.innerText.toLowerCase().includes(this.search.toLowerCase());
          }
       }">
  <a href="/admin" class="brand-link">
    <img src="{{ asset('frontend/img/logo.png') }}" alt="AutoSpaceLink" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">AutoSpareLink</span>
  </a>

  <div class="sidebar">
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
      <div class="image">
        <img src="{{ Auth::user()->avatar ?? 'https://www.gravatar.com/avatar/?d=mp&s=200' }}" class="img-circle elevation-2" alt="Avatar">
      </div>
      <div class="info">
        <a href="#" class="d-block text-light fw-bold">
          {{ Auth::user()->name }}
          <small class="d-block text-muted">{{ Auth::user()->getRoleNames()->first() }}</small>
        </a>
      </div>
    </div>

    <div class="form-inline">
      <div class="input-group">
        {{-- Bound input to Alpine 'search' variable --}}
        <input class="form-control form-control-sidebar" 
               type="search" 
               x-model="search" 
               placeholder="Search menu..." 
               aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar" @click="search = ''">
            <i class="fas" :class="search === '' ? 'fa-search' : 'fa-times'"></i>
          </button>
        </div>
      </div>
    </div>

    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-item" x-show="isVisible($el)">
          <a href="/admin" class="nav-link">
            <i class="nav-icon fas fa-gauge"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-header" x-show="search === ''">CONTENT MANAGEMENT</li>

        <li class="nav-item has-treeview" x-show="isVisible($el)" :class="search !== '' ? 'menu-open' : ''">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-car"></i>
            <p>Vehicle Management <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/vehicle-brands" class="nav-link"><i class="fas fa-tags nav-icon"></i><p>Vehicle Brands</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/vehicle-models" class="nav-link"><i class="fas fa-car-side nav-icon"></i><p>Models</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/variants" class="nav-link"><i class="fas fa-cogs nav-icon"></i><p>Variants</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/specifications" class="nav-link"><i class="fas fa-cogs nav-icon"></i><p>Specification</p></a></li>
            <li class="nav-item d-none" x-show="isVisible($el)"><a href="/admin/body-types" class="nav-link"><i class="fas fa-car nav-icon"></i><p>Body Types</p></a></li>
            <li class="nav-item d-none" x-show="isVisible($el)"><a href="/admin/engine-types" class="nav-link"><i class="fas fa-cog nav-icon"></i><p>Engine Types</p></a></li>
            <li class="nav-item d-none" x-show="isVisible($el)"><a href="/admin/transmission-types" class="nav-link"><i class="fas fa-exchange-alt nav-icon"></i><p>Transmission Types</p></a></li>
            <li class="nav-item d-none" x-show="isVisible($el)"><a href="/admin/drive-types" class="nav-link"><i class="fas fa-road nav-icon"></i><p>Drive Types</p></a></li>
           </ul>
        </li>

        <li class="nav-item has-treeview" x-show="isVisible($el)" :class="search !== '' ? 'menu-open' : ''">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Spare Parts <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/spare-parts" class="nav-link"><i class="fas fa-boxes nav-icon"></i><p>Parts</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/part-brands" class="nav-link"><i class="fas fa-road nav-icon"></i><p>Part Brands</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/categories" class="nav-link"><i class="fas fa-road nav-icon"></i><p>Categories</p></a></li>
          </ul>
        </li>

        <li class="nav-item has-treeview" x-show="isVisible($el)" :class="search !== '' ? 'menu-open' : ''">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>E-Commerce <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/carts" class="nav-link"><i class="fas fa-shopping-cart nav-icon"></i><p>Carts</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/orders" class="nav-link"><i class="fas fa-receipt nav-icon"></i><p>Orders</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/payments" class="nav-link"><i class="fas fa-credit-card nav-icon"></i><p>Payments</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/shipments" class="nav-link"><i class="fas fa-truck nav-icon"></i><p>Shippings</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/addresses" class="nav-link"><i class="fas fa-map-marker-alt nav-icon"></i><p>Addresses</p></a></li>
          </ul>
        </li>

        <li class="nav-item has-treeview" x-show="isVisible($el)" :class="search !== '' ? 'menu-open' : ''">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Users & Access <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/users" class="nav-link"><i class="fas fa-user nav-icon"></i><p>Users</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/roles-and-permissions" class="nav-link"><i class="fas fa-user-shield nav-icon"></i><p>Roles & Permissions</p></a></li>
          </ul>
        </li>

        <li class="nav-item has-treeview" x-show="isVisible($el)" :class="search !== '' ? 'menu-open' : ''">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-pie"></i>
            <p>Reports & Settings <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/bloggers" class="nav-link"><i class="fas fa-chart-line nav-icon"></i><p>Sales Reports</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/blogCategories" class="nav-link"><i class="fas fa-clipboard-list nav-icon"></i><p>Inventory Reports</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="/admin/blogCategories" class="nav-link"><i class="fas fa-cog nav-icon"></i><p>System Settings</p></a></li>
          </ul>
        </li>

        <li class="nav-header" x-show="search === ''">COMMUNITY & ENGAGEMENT</li>

        <li class="nav-item" x-show="isVisible($el)"><a href="/admin/careers" class="nav-link"><i class="nav-icon fas fa-briefcase"></i><p>Careers</p></a></li>
        <li class="nav-item" x-show="isVisible($el)"><a href="/admin/applications" class="nav-link"><i class="nav-icon fas fa-gear"></i><p>Applications</p></a></li>

        <li class="nav-item has-treeview" x-show="isVisible($el)" :class="search !== '' ? 'menu-open' : ''">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-envelope"></i>
            <p>Mailbox <i class="fas fa-angle-left right"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item" x-show="isVisible($el)"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Inbox</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Compose</p></a></li>
            <li class="nav-item" x-show="isVisible($el)"><a href="#" class="nav-link"><i class="far fa-circle nav-icon"></i><p>Read</p></a></li>
          </ul>
        </li>

        @if(Auth::check())
        <li class="nav-item mt-3 border-top border-secondary">
          <a href="#" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="nav-icon fas fa-right-from-bracket"></i>
            <p>Logout</p>
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </li>
        @endif

      </ul>
    </nav>
  </div>
</aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; {{ date('Y') }} <a href="/">AutoSpareLink</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('back/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('back/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('back/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('back/plugins/chart.js/Chart.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('back/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('back/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('back/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('back/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('back/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('back/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('back/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('back/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('back/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('back/dist/js/adminlte.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('back/dist/js/customize.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('back/dist/js/pages/dashboard.js') }}"></script>
<!-- DataTables  & Plugins -->
<script src="{{ asset('back/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('back/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('back/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('back/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('back/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('back/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('back/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('back/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('back/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('back/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('back/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('back/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('back/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') }}"></script>
 <x-head.tinymce-config/>
<!-- Page specific script -->
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>
  @stack('scripts')
  @livewireScripts
</body>
</html>
