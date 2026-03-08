<template>
    <Head title="HFRO - Login" />
    <div class="hold-transition login-page">
        <div class="login-box">
  <div class="login-logo">
    <Link href="/admin">HFRO</Link>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="../../index3.html" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
<hr>
      <p class="mb-1">
        <Link href="/admin/forgot-password">I forgot my password</Link>
      </p>
      <p class="mb-0">
        New to HFRO? <Link href="/admin/register" class="text-center">Register</Link>
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