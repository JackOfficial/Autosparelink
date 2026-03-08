<template>
     <Head title="HFRO - Recover Password" />
     <div class="hold-transition login-page">
    <div class="login-box">
  <div class="login-logo">
    <Link href="/admin">HFRO</Link>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>

      <form action="login.html" method="post">
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Confirm Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Change password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1">
        <Link href="/admin/login">Login</Link>
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