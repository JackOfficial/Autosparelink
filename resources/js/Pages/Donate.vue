<template>
	<Head title="Happy Family - Donate" />
	<Layout>
  
  <!--
  End Fixed Navigation
  ==================================== -->
  
  <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Our team.jpeg');">
	  <div class="container">
		  <div class="row">
			  <div class="col-md-12">
				  <h2>Donate</h2>
				  <ol class="breadcrumb header-bradcrumb">
					<li><Link href="/">Home</Link></li>
					<li class="mx-1">/</li>
					<li class="active">Donate</li>
				  </ol>
			  </div>
		  </div>
	  </div>
  </section>
  
 <section class="section container">
	<div class="title text-center">
					  <h2>Donate</h2>
<p>At Happy Family Rwanda Organization (HFRO), we are passionate about addressing the challenges faced by underprivileged communities around the world. Through our various programs and initiatives, we strive to make a positive impact in the lives of individuals and promote sustainable change.</p>
		</div>
   <div class="row">
	<div class="col-md-6">
	<img src="/Front/images/Volunteers.jpg" class="img img-responsive w-100" /> 
	</div>
	<div class="col-md-6">
    <div v-if="$page.props.flash.message" class="alert alert-success">{{ $page.props.flash.message }}</div>
		<form @submit.prevent="donate">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="name">Name on the card*</label>
                  <input type="text" v-model="form.name" id="name" class="form-control" required />
                  <div v-if="form.errors.name" v-text="form.errors.name" class="text-danger"></div>
                </div>
                        </div>
                        <div class="col-md-6">
							<div class="form-group">
                  <label for="card_number">Card Number*</label>
                  <input type="text" v-model="form.card_number" id="card_number" class="form-control" required />
                  <div v-if="form.errors.card_number" v-text="form.errors.card_number" class="text-danger"></div>
                </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="cvv">CVV*</label>
                  <input type="number" v-model="form.cvv" id="cvv" class="form-control" required />
                  <div v-if="form.errors.cvv" v-text="form.errors.cvv" class="text-danger"></div>
                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="expiry_date">Expiry date*</label>
                  <input type="text" v-model="form.expiry_date" id="expiry_date" class="form-control" required />
                  <div v-if="form.errors.expiry_date" v-text="form.errors.expiry_date" class="text-danger"></div>
                </div>
                        </div>
                    </div>
                <div class="form-group">
                  <label for="amount">Amount*</label>
                  <input type="number" v-model="form.amount" id="amount" class="form-control" required />
                  <div v-if="form.errors.amount" v-text="form.errors.amount" class="text-danger"></div>
                </div>
                
                <hr />
                <div class="form-group">
            <button type="submit" class="btn btn-primary w-100" :disabled="form.processing"> 
                <span v-if="!form.processing"><i  class="fa fa-heart"></i> Donate Now</span> 
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
    card_number: null,
    cvv: null,
    expiry_date: null,
    amount: null
});

let donate = () =>{
    form.post('/donate');
};
  </script>