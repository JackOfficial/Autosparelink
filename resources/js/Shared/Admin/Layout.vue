<template class="register">
    <div class="wrapper">
  <!-- Preloader -->
  <!-- <Preloader /> -->

  <!-- Navbar -->
  <Navbar />
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <Sidebar />

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <slot />
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <Footer />

  <!-- Control Sidebar -->
  <Customization />
  <!-- /.control-sidebar -->
</div>
  </template>

<script>
import Navbar from "./Navbar.vue";
import Sidebar from "./Sidebar.vue";
import Footer from "./Footer.vue";
import Customization from "./Customization.vue";
export default{
components: { Navbar, Sidebar, Footer, Customization }
}
</script>