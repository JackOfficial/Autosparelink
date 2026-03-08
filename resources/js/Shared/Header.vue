<template>
    <header class="navigation fixed-top">
  <div class="container">
    <!-- main nav -->
    <nav class="navbar navbar-expand-lg navbar-light">
      <!-- logo -->
      <Link class="navbar-brand logo" href="/">
        <img class="logo-default" src="/Front/images/hfro black logo.png" style="width: 200px;" alt="logo"/>
        <img class="logo-white" src="/Front/images/hfro white logo.png" style="width: 200px;" alt="logo"/>
      </Link>
      <!-- /logo -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
        aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navigation">
        <ul class="navbar-nav ml-auto text-center">
          <li class="nav-item" :class="{'active': $page.url === '/'}">
            <Link class="nav-link" href="/">Home</Link>
          </li>
          <li class="nav-item" :class="{'active': $page.url === '/about'}">
            <Link class="nav-link" href="/about">About Us</Link>
          </li>
          <li class="nav-item" :class="{'active': $page.url === '/gallery'}">
            <Link class="nav-link" href="/gallery">Gallery</Link>
          </li>
          <li class="nav-item" :class="{'active': $page.url.startsWith('/causes')}">
            <Link class="nav-link" href="/causes">Causes</Link>
          </li>
          <li class="nav-item" :class="{'active': $page.url.startsWith('/projects')}">
            <Link class="nav-link" href="/projects">Projects</Link>
          </li>
          <li class="nav-item" :class="{'active': $page.url.startsWith('/blogs')}">
            <Link class="nav-link" href="/blogs">Blogs</Link>
          </li>
          <li class="nav-item" :class="{'active': $page.url === '/contact'}">
            <Link class="nav-link" href="/contact">Contact</Link>
          </li>
          <li class="nav-item" :class="{'active': $page.url === '/career'}">
            <Link class="nav-link" href="/career">Careers</Link>
          </li>
          <li class="nav-item dropdown d-none">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              Pages
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="#">404 Page</a>
              <Link class="dropdown-item" href="/blogs">Blog Page</Link>
              <Link class="dropdown-item" href="/blog">Blog Single Page</Link>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- /main nav -->
  </div>
</header>
</template>

<script>
import { Link } from '@inertiajs/vue3'
export default{
 components: { Link },
};
</script>