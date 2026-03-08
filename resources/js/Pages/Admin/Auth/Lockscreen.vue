<template>
    <Head title="HFRO - Lockscreen" />
    <div class="hold-transition lockscreen">
        <div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <Link href="/admin">HFRO</Link>
  </div>
  <!-- User name -->
  <div class="lockscreen-name">John Doe</div>

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    <div class="lockscreen-image">
      <img src="Back/dist/img/user1-128x128.jpg" alt="User Image">
    </div>
    <!-- /.lockscreen-image -->

    <!-- lockscreen credentials (contains the form) -->
    <form class="lockscreen-credentials">
      <div class="input-group">
        <input type="password" class="form-control" placeholder="password">

        <div class="input-group-append">
          <button type="button" class="btn">
            <i class="fas fa-arrow-right text-muted"></i>
          </button>
        </div>
      </div>
    </form>
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
    Enter your password to retrieve your session
  </div>
  <div class="text-center">
    <Link href="/admin/login">Or sign in as a different user</Link>
  </div>
  <div class="lockscreen-footer mt-5 text-center">
    Copyright &copy; 2014-2021 <b><Link href="/admin" class="text-black">HFRO</Link></b><br>
    All rights reserved
  </div>
</div>
</div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Layout from "../../../Shared/Admin/Layout.vue";
import Preloader from "../../../Shared/Admin/Preloader.vue";
// defineProps({ name: Object })
export default{
props: {},
components: { Layout, Preloader, Head, Link },
props:{
  name: String,
  Techstack: Array,
},
  methods: { },
data(){
  return {}
}
}
</script>