<template>
    <Head title="Happy Family - Courses" />
    <Layout>
        <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Our team.jpeg');">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Causes</h2>
				<ol class="breadcrumb header-bradcrumb">
				  <li><a href="/">Home</a></li>
          <li class="mx-1">/</li>
				  <li class="active">Causes</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="section container">

  <div class="title text-center">
				<h2>Our Causes</h2>
				<p>At Happy Family Rwanda Organization (HFRO), we are passionate about addressing the challenges faced by underprivileged communities around the world. Through our various programs and initiatives, we strive to make a positive impact in the lives of individuals and promote sustainable change.</p>
				<p>Here are the causes we support:</p>
        <div class="border"></div>
			</div>

 <div class="row">
<div class="col-md-4 mb-5" v-for="cause in causes" :key="cause.id">
  <div class="card w-100">
  <img :src="`/storage/${cause.photo}`" class="card-img-top" :alt="cause.cause">
  <div class="card-body">
   <div class="card-title">
    <span class="badge badge-primary">{{ cause.cause }}</span>
      </div>
    <p class="card-text">{{ cause.description }}</p>
    <!-- <div class="d-none d-flex justify-content-between">
      <div>Donated</div>
      <div>$2000</div>
    </div> -->
  </div>
</div>
</div>

<div class="col-md-12 mb-5 text-center" v-if="causes.length == 0">
No Cause available at the moment!
</div>
 </div>
</section>

<section class="container jumbotron text-center">
  <div class="">
      <p>Each cause we support is carefully chosen based on the needs of the communities we work with and the potential for sustainable impact.</p> 
      <p>With your support, we can continue to make a difference and bring hope to those who need it most.</p> 
      <p>Join us in our mission by donating, volunteering, or spreading awareness about the causes we champion. Together, we can build a better future for all.</p>
    </div>
   <div>
    <Link href="/donate" class="btn btn-primary rounded">Donate Now</Link>
  </div>
</section>

    </Layout>
  </template>

<script>
// import Layout from './Layout'
import { Head, Link } from '@inertiajs/vue3';
import Layout from "../Shared/Layout.vue";

// defineProps({ name: Object })
export default{
  components: { Layout, Head, Link },
  props: {
    causes: Object,
  },
    methods: { },
  data(){
    return { }
  }
}
</script>