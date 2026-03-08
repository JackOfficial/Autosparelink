<template>
	<Head title="Happy Family - Volunteer" />
	<Layout>
  
  <!--
  End Fixed Navigation
  ==================================== -->
  
  <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Our team.jpeg');">
	  <div class="container">
		  <div class="row">
			  <div class="col-md-12">
				  <h2>Volunteer</h2>
				  <ol class="breadcrumb header-bradcrumb">
					<li><Link href="/">Home</Link></li>
					<li class="mx-1">/</li>
					<li class="active">Volunteer</li>
				  </ol>
			  </div>
		  </div>
	  </div>
  </section>
  
 <section class="section container">
	<div class="title text-center">
					  <h2>Volunteer</h2>
<p>At Happy Family Rwanda Organization (HFRO), we are passionate about addressing the challenges faced by underprivileged communities around the world. Through our various programs and initiatives, we strive to make a positive impact in the lives of individuals and promote sustainable change.</p>
		</div>
   <div class="row">
	<div class="col-md-6">
	<img src="/Front/images/Volunteers.jpg" class="img img-responsive w-100" /> 
	</div>
	<div class="col-md-6">
		<div v-if="$page.props.flash.message" class="alert alert-success">{{ $page.props.flash.message }}</div>
		<form @submit.prevent="save">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="name">Full Name</label>
                  <input type="text" v-model="form.name" id="name" class="form-control" required />
                  <div v-if="form.errors.name" v-text="form.errors.name" class="text-danger"></div>
                </div>
                        </div>
                        <div class="col-md-6">
							<div class="form-group">
                  <label for="dob">Date of birth</label>
                  <input type="date" v-model="form.dob" id="dob" class="form-control" required />
                  <div v-if="form.errors.dob" v-text="form.errors.dob" class="text-danger"></div>
                </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" v-model="form.email" id="email" class="form-control" required />
                  <div v-if="form.errors.email" v-text="form.errors.email" class="text-danger"></div>
                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="phone">Mobile Phone</label>
                  <input type="text" v-model="form.phone" id="phone" class="form-control" required />
                  <div v-if="form.errors.phone" v-text="form.errors.phone" class="text-danger"></div>
                </div>
                        </div>
                    </div>
                <div class="form-group">
                  <label for="reason">Why do you want to join us?</label>
                  <textarea id="reason" v-model="form.reason" class="form-control" rows="2"></textarea>
                  <div v-if="form.errors.reason" v-text="form.errors.reason" class="text-danger"></div>
                </div>
                
                <hr />
                <div class="form-group d-flex justify-content-between ">
            <a href="#" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary" :disabled="form.processing"> 
                <span v-if="!form.processing"><i  class="fa fa-plus"></i> Submit</span> 
                <span v-else-if="form.processing"><div class="spinner-border spinner-border-sm" role="status"></div> Submitting...</span>
            </button>
          </div>
                </form>
  </div>
  </div>
  </section>

  <section class="call-to-action section">
	  <div class="container">
		  <div class="row">
			  <div class="col-md-12 text-center">
				  <h2>Together, we can make a difference.</h2>
				  <p>Together, let's build a better future, 
					  empower those in need, and create a world where everyone can thrive.</p>
				  <p>We cannot achieve our goals alone. 
					  We rely on the support and generosity of individuals, corporations, and 
					  foundations to continue our life-changing work. Your contributions, whether 
					  through financial donations, in-kind support, or volunteering, make a real difference 
					  in the lives of those we serve. Together, we can bring hope and transform communities, 
					  one step at a time.</p>
				  <Link href="/contact" class="btn btn-main">Contact Us</Link>
			  </div>
		  </div> 		<!-- End row -->
	  </div>   	<!-- End container -->
  </section>   <!-- End section -->
	</Layout>
  </template>
  
  <script setup>
  // import Layout from './Layout'
  import { Head, Link, useForm } from '@inertiajs/vue3';
  import Layout from "../Shared/Layout.vue";
import { defineComponent } from 'vue';
   defineComponent({
	Layout, Head, Link, useForm
   });
   defineProps({
	name: Object
   });
   let form = useForm({
    name: null,
    dob: null,
    email: null,
    phone: null,
    reason: null,
});

let save = () =>{
    form.post('/volunteer');
};
  </script>