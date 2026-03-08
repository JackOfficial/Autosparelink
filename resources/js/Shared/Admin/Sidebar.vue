<template>
     <aside class="main-sidebar sidebar-dark-pink elevation-4">
    <!-- Brand Logo -->
    <Link href="/admin" class="brand-link">
      <img src="/Front/images/hfro logo.png" alt="HFRO Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">HFRO</span>
    </Link>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/Back/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ $page.props.user.name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <Link href="/admin" class="nav-link" :class="{'active': $page.url === '/admin'}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </Link>
          </li>
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Widgets
                <span class="right badge badge-primary">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Blogs
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <Link href="/admin/bloggers" class="nav-link" :class="{'active': $page.url === '/admin/bloggers'}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Bloggers</p>
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/admin/blogCategories" class="nav-link" :class="{'active': $page.url === '/admin/blogCategories'}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Blog Categories</p>
                </Link>
              </li>
              <li class="nav-item">
                <Link href="/admin/blogs" class="nav-link" :class="{'active': $page.url === '/admin/blogs'}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Blogs</p>
                </Link>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <Link href="/admin/stories" class="nav-link" :class="{'active': $page.url === '/admin/stories'}">
              <i class="nav-icon far fa-image"></i>
              <p>
                Stories
              </p>
            </Link>
          </li>
          <li class="nav-item">
            <Link href="/admin/causes" class="nav-link" :class="{'active': $page.url === '/admin/causes'}">
              <i class="nav-icon far fa-image"></i>
              <p>
                Causes
              </p>
            </Link>
          </li>
          <li class="nav-item">
            <Link href="/admin/projects" class="nav-link" :class="{'active': $page.url === '/admin/projects'}">
              <i class="nav-icon far fa-image"></i>
              <p>
                Projects
              </p>
            </Link>
          </li>

          <li class="nav-item">
            <Link href="/admin/gallery" class="nav-link" :class="{'active': $page.url === '/admin/gallery'}">
              <i class="nav-icon far fa-image"></i>
              <p>
                Gallery
              </p>
            </Link>
          </li>

          <li class="nav-item">
            <Link href="/admin/team" class="nav-link" :class="{'active': $page.url === '/admin/team'}">
              <i class="nav-icon far fa-image"></i>
              <p>
                Team
              </p>
            </Link>
          </li>

          <li class="nav-item">
            <Link href="/admin/partners" class="nav-link" :class="{'active': $page.url === '/admin/partners'}">
              <i class="nav-icon far fa-image"></i>
              <p>
                Partners
              </p>
            </Link>
          </li>

          <li class="nav-item">
            <Link href="/admin/careers" class="nav-link" :class="{'active': $page.url === '/admin/careers'}">
              <i class="nav-icon far fa-image"></i>
              <p>
                Careers
              </p>
            </Link>
          </li>

          <li class="nav-item">
            <Link href="/admin/volunteers" class="nav-link" :class="{'active': $page.url === '/admin/volunteers'}">
              <i class="nav-icon far fa-image"></i>
              <p>
                Volunteers
              </p>
            </Link>
          </li>

          <li class="nav-item">
            <Link href="/admin/events" class="nav-link" :class="{'active': $page.url === '/admin/events'}">
              <i class="nav-icon far fa-image"></i>
              <p>
                Events
              </p>
            </Link>
          </li>

          <li class="nav-item">
            <Link href="/admin/applications" class="nav-link" :class="{'active': $page.url === '/admin/applicatios'}">
              <i class="nav-icon far fa-image"></i>
              <p>
                Applications
              </p>
            </Link>
          </li>
          <li class="nav-item">
            <Link href="/admin/users" class="nav-link" :class="{'active': $page.url === '/admin/users'}">
              <i class="nav-icon far fa-image"></i>
              <p>
                Users
              </p>
            </Link>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-envelope"></i>
              <p>
                Mailbox
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inbox</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Compose</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Read</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-header">LABELS</li>
          <li class="nav-item">
            <Link href="/admin/pages" class="nav-link">
              <i class="nav-icon far fa-circle text-danger"></i>
              <p class="text">Pages</p>
            </Link>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-circle text-warning"></i>
              <p>Warning</p>
            </a>
          </li>
          <li class="nav-item border border-rounded border-primary">
            <a role="button" @click="logout" class="nav-link">
              <i class="nav-icon far fa-circle text-info"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>

<script>
import { Link, router } from '@inertiajs/vue3'
export default{
 components: { Link },
 methods: {
     logout(){
      router.post('/logout');
     }
 },
};
</script>

