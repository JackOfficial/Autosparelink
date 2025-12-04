<template>
     <Head title="HFRO - Register" />
     <div class="hold-transition register-page">

  
    <div class="register-box">
  <div class="register-logo">
    <Link href="/admin">HFRO</Link>
  </div>

  <div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new membership</p>

      <form action="register" method="POST">
        <div class="input-group mb-3">
          <input type="text" name="name" class="form-control" placeholder="Full name" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password-confirm" class="form-control" placeholder="Retype password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div>
         <p class="small">By signing up, you agree to HFRO terms and conditions</p>
         <button type="submit" class="btn btn-primary btn-block">Register</button>
        </div>
      </form>
      <div class="mt-2"><Link href="/admin/login">I already have account</Link></div>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
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