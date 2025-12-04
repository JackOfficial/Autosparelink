<template>
    <Head title="Happy Family - Careers" />
    <Layout>
        <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Career.png');">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Careers</h2>
				<ol class="breadcrumb header-bradcrumb">
				  <li><a href="/">Home</a></li>
          <li class="mx-1">/</li>
				  <li class="active">Careers</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<div class="container mt-5">

  <div class="title text-center">
				<h2>Our Careers</h2>
				<p>At Happy Family Rwanda Organization (HFRO), we are passionate about addressing the challenges faced by underprivileged communities around the world. Through our various programs and initiatives, we strive to make a positive impact in the lives of individuals and promote sustainable change.</p>
			</div>
      <div class="title text-center">
				<h2>{{ careers_counter }} Job {{ (careers_counter>1) ? 'Posts' : 'Post' }}</h2>
				<div class="border"></div>
			</div>
 <div class="row">
<div class="col-md-4 mb-5" v-for="career in careers" :key="career.id">
  <div class="card bg-primary">
      <div class="card-body">
        <h5 class="card-title text-white">{{ career.title }}</h5>
        <p class="card-text"><div class="badge badge-light">{{ career.jobtype }}</div></p>
        <p class="card-text"><i class="fa fa-calendar"></i> Posted on: {{ career.created_at }}</p>
        <Link :href="`/job-details/${career.id}`" class="btn btn-sm rounded-pill btn-outline-light">Read More</Link>
      </div>
    </div>
</div>

<div class="col-md-12 mb-5 text-center" v-if="careers.length == 0">
    No Job available at the moment!
</div>
 </div>
</div>

<div class="container jumbotron text-center">
  <div class="">
      <p>Each cause we support is carefully chosen based on the needs of the communities we work with and the potential for sustainable impact.</p> 
      <p>With your support, we can continue to make a difference and bring hope to those who need it most.</p> 
      <p>Join us in our mission by donating, volunteering, or spreading awareness about the causes we champion. Together, we can build a better future for all.</p>
    </div>
   <div>
    <Link href="/donate" class="btn btn-primary rounded">Donate Now</Link>
  </div>
</div>

    </Layout>
  </template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Layout from "../Shared/Layout.vue";
export default{
  components: { Layout, Head, Link },
  props: {
    careers: Object,
    careers_counter: Number,
  },
    methods: { },
  data(){
    return { }
  }
}
</script>