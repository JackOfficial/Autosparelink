<template>
    <footer id="footer" class="bg-one">
  <div class="top-footer">
    <div class="container">
      <div class="row">
        <div class="col-sm-3 col-md-3 col-lg-3">
          <h3>about Us</h3>
          <p>We are a compassionate NGO dedicated to creating positive change and making a lasting impact in communities worldwide.</p>
        </div>
        
        <div class="col-sm-3 col-md-3 col-lg-3">
          <ul>
            <li><h3>Quick Links</h3></li>
            <li><Link href="/about">About</Link></li>
            <li><Link href="/causes">Causes</Link></li>
            <li><Link href="/blogs">Blogs</Link></li>
            <li><Link href="/contact">Contact</Link></li>
          </ul>
        </div>

        <div class="col-sm-3 col-md-3 col-lg-3">
          <ul>
            <li><h3>Other Links</h3></li>
            <li><Link href="/gallery">Gallery</Link></li>
            <li><Link href="/events">Events</Link></li>
            <li><Link href="/projects">Projects</Link></li>
            <li><Link href="/volunteer">Volunteers</Link></li>
            <li><Link href="/donate">Donate</Link></li>
           
          </ul>
        </div>

        <div class="col-sm-3 col-md-3 col-lg-3">

          <div class="mb-5">
              <h3 class="">Subscribe to Newsletter</h3>
              <div v-if="$page.props.flash.message" class="alert alert-success">{{ $page.props.flash.message }}</div>
              <form @submit.prevent="subcribe">
                <div class="input-group mb-3">
                  <input type="email" v-model="form.email"  class="form-control rounded-0 border-secondary text-white bg-transparent" placeholder="Enter Email" aria-label="Enter Email" aria-describedby="subscribe">
                  <div class="input-group-append">
                    <button class="btn btn-primary text-white" type="submit" id="subscribe"> 
                      <span v-if="!form.processing">Subscribe</span> 
                <span v-else-if="form.processing"><div class="spinner-border spinner-border-sm" role="status">
                </div> Subscribe</span></button>
                  </div>
                  <div v-if="form.errors.email" v-text="form.errors.email" class="text-danger mt-1"></div>
                </div>
                
              </form>
              <h3>Connect with us</h3>
              <div class="d-flex">
                <a href="#" class="mx-1">Facebook</a>
            <a href="https://twitter.com/HFRwOrg" alt="__blank" class="mx-1">Twitter</a>
            <a href="https://www.youtube.com/channel/UCbWRXU7KOSRxNodro3H8mug" alt="__blank" class="mx-1">Youtube</a>
            <a href="https://www.linkedin.com/company/happy-family-rwanda" alt="__blank" class="mx-1">LinkedIn</a>
              </div>
            </div>
        </div>
        <!-- End of .col-sm-3 -->

      </div>
    </div> <!-- end container -->
  </div>
  <div class="footer-bottom">
    <h5>Copyright 2023. All rights reserved.</h5>

    <button @click="logout" class="dropdown-item text-dark d-none" >Logout</button>

  </div>
</footer> <!-- end footer -->
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { defineComponent } from 'vue';
defineComponent({ Link });
const form = useForm({
    email: null, 
    __preserveScroll: true,
});

const subcribe = () =>{
  __preserveScroll: true;
    form.post('/subscribe');
};

const logout = () =>{
    form.post('/logout');
};

</script>
