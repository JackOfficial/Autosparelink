<template>
    <Head title="HFRO - Forgot Password" />
    <div class="hold-transition login-page">
        <div class="login-box">
  <div class="login-logo">
    <a href="/">HFRO</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

      <form action="recover-password.html" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Request new password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1">
        <Link href="/admin/login">Login</Link>
      </p>
      <p class="mb-0">
        <Link href="/register" class="text-center">Register a new membership</Link>
      </p>
    </div>
    <!-- /.login-card-body -->
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