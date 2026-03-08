<template>
	<Head title="Happy Family - Projects" />
	<Layout>
  
  <!--
  End Fixed Navigation
  ==================================== -->
  
  <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Our team.jpeg');">
	  <div class="container">
		  <div class="row">
			  <div class="col-md-12">
				  <h2>Our Projects</h2>
				  <ol class="breadcrumb header-bradcrumb">
					<li><Link href="/">Home</Link></li>
					<li class="mx-1">/</li>
					<li class="active">Our Projects</li>
				  </ol>
			  </div>
		  </div>
	  </div>
  </section>
  
 <section class="section container text-center">
	<div class="title text-center">
					  <h2>Our Projects</h2>
<p>At Happy Family Rwanda Organization (HFRO), we are passionate about addressing the challenges faced by underprivileged communities around the world. Through our various programs and initiatives, we strive to make a positive impact in the lives of individuals and promote sustainable change.</p>
		</div>
   <div class="row">
	<div class="col-md-4" v-for="project in projects.data" :key="project.id">
		<a :href="`/project/${project.id}`">
			<div class="card w-100">
	<img :src="`/storage/${project.photo}`" class="card-img-top" :alt="project.project">
	<div class="card-body">
	 <div class="card-title">
	  <span class="badge badge-primary">{{ project.cause }}</span>
		</div>
	  <p class="card-text">{{ project.project }}</p>
	  <div v-if="project.budget != null" class="d-flex justify-content-between">
		<div>Budget</div>
		<div>${{ project.budget.toLocaleString() }}</div>
	  </div>
	</div>
  </div>
		</a>
	</div>
	<div class="col-md-12 mb-5 text-center" v-if="projects.data.length == 0">
     No Project available at the moment!
  </div>
  </div>
  <Pagination :links="projects.links" class="mt-5" />
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
  
  <script>
  // import Layout from './Layout'
  import { Head, Link } from '@inertiajs/vue3';
  import Layout from "../Shared/Layout.vue";
  import Pagination from "../Shared/Pagination.vue";

  // defineProps({ name: Object })
  export default{
	components: { Layout, Head, Link, Pagination },
	props: {
	  projects: Object
	},
	data(){
	  return { }
	}
  }
  
  </script>