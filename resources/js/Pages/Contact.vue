<template>
    <Head title="Happy Family - Contact" />
    <Layout>
        <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Our team.jpeg');">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Contact Us</h2>
				<ol class="breadcrumb header-bradcrumb">
				  <li><Link href="/">Home</Link></li>
				  <li class="active">Contact Us</li>
				</ol>
			</div>
		</div>
	</div>
</section>

 <!--Start Contact Us
	=========================================== -->		
<section class="contact-us" id="contact">
	<div class="container">
		<div class="row">
			
			<!-- section title -->
			<div class="col-12">
			<div class="title text-center" >
				<h2>Get In Touch</h2>
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptate facilis eveniet maiores ab maxime nam ut numquam molestiae quaerat incidunt?</p>
				<div class="border"></div>
			</div>
			</div>
			<!-- /section title -->
			
			<!-- Contact Details -->
			<div class="contact-details col-md-6 " >
				<h3>Contact Details</h3>
				<p>For any inquiry, don't hesitate to reach out to us via below contact address.</p>
				<img src="/images/norrsken.jpg" class="w-100 img img-responsive" />
				<ul class="contact-short-info" >
					<li>
						<i class="tf-ion-ios-home"></i>
						<span>1 KN 78 St, Kigali - Nyarugenge, Norrsken</span>
					</li>
					<li>
						<i class="tf-ion-android-phone-portrait"></i>
						<span>Phone: (+250) 788708314</span>
					</li>
					<li>
						<i class="tf-ion-android-mail"></i>
						<span>Email: info@hfro.org</span>
					</li>
				</ul>
				<!-- Footer Social Links -->
				<div class="social-icon">
					<ul>
						<li><a href="#"><i class="tf-ion-social-facebook"></i></a></li>
						<li><a href="https://twitter.com/HFRwOrg" alt="__blank"><i class="tf-ion-social-twitter"></i></a></li>
						<li><a href="https://www.youtube.com/channel/UCbWRXU7KOSRxNodro3H8mug" alt="__blank"><i class="tf-ion-social-youtube-outline"></i></a></li>
						<li><a href="https://www.linkedin.com/company/happy-family-rwanda" alt="__blank"><i class="tf-ion-social-linkedin-outline"></i></a></li>
                    </ul>
				</div>
				<!--/. End Footer Social Links -->
			</div>
			<!-- / End Contact Details -->
				
			<!-- Contact Form -->
			<div class="contact-form col-md-6 " >
				<div v-if="$page.props.flash.message" class="alert alert-success">{{ $page.props.flash.message }}</div>
				<form @submit.prevent="send">
					<div class="form-group">
						<input type="text" placeholder="Your Name" class="form-control" v-model="form.name" id="name" required />
						<div v-if="form.errors.name" v-text="form.errors.name" class="text-danger"></div>
					</div>
					
					<div class="form-group">
						<input type="email" placeholder="Your Email" class="form-control" v-model="form.email" id="email" required />
						<div v-if="form.errors.email" v-text="form.errors.email" class="text-danger"></div>
					</div>
					
					<div class="form-group">
						<input type="text" placeholder="Subject" class="form-control" v-model="form.subject" id="subject" required />
						<div v-if="form.errors.subject" v-text="form.errors.subject" class="text-danger"></div>
					</div>
					
					<div class="form-group">
						<textarea rows="6" placeholder="Message" class="form-control" v-model="form.message" id="message" required></textarea>	
						<div v-if="form.errors.message" v-text="form.errors.message" class="text-danger"></div>
					</div>
					<div id="cf-submit">
						<button type="submit" id="contact-submit" class="btn btn-transparent btn btn-primary" :disabled="form.processing"> 
                <span v-if="!form.processing">Send <i  class="fa fa-send"></i></span> 
                <span v-else-if="form.processing"><div class="spinner-border spinner-border-sm" role="status"></div> Sending...</span>
            </button>
		</div>						
					
				</form>
			</div>
			<!-- ./End Contact Form -->
		
		</div> <!-- end row -->
		<div class="mt-3 embed-responsive embed-responsive-16by9">
			<iframe src="https://www.google.com/maps/embed?pb=!1m26!1m12!1m3!1d63800.78981807256!2d30.035565795219192!3d-1.932389900791949!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m11!3e6!4m3!3m2!1d-1.9170551!2d30.088831799999998!4m5!1s0x19dca5a86d814c61%3A0x7d3b83e12b1c11a9!2snorrsken%20kigali!3m2!1d-1.9511728!2d30.0599867!5e0!3m2!1sen!2srw!4v1699281438213!5m2!1sen!2srw" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
		</div>
	</div> <!-- end container -->
</section> <!-- end section -->
    </Layout>
  </template>

<script setup>
// import Layout from './Layout'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { defineComponent } from 'vue';
import Layout from "../Shared/Layout.vue";

defineProps({ 'pageName': String })
defineComponent({
	Layout, Head, Link
})
const form = useForm({
    name: null,
	email: null,
	subject: null,
	message: null
});

const send = () =>{
	form.post('/contact')
};
</script>