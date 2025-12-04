<template>
    <Head title="Happy Family - Job details" />
    <Layout>
        <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Career.png');">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Job Details</h2>
				<ol class="breadcrumb header-bradcrumb">
				  <li><a href="/">Home</a></li>
          <li class="mx-1">/</li>
				  <li class="active">Job details</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="container mt-3">

  <div class="title text-center">
				<h2>Our Careers</h2>
				<p>At Happy Family Rwanda Organization (HFRO), we are passionate about addressing the challenges faced by underprivileged communities around the world. Through our various programs and initiatives, we strive to make a positive impact in the lives of individuals and promote sustainable change.</p>
				<div class="border"></div>
			</div>

 <div class="row">
<div class="col-md-2"></div>
<div class="col-md-8">
  <h2>{{ jobDetails.title }} {{ jobDetails.jobtype }} Job</h2> 
  <hr />
  <p>
    <h3>Job Description</h3>
   <div v-html="jobDetails.description"></div>
  </p>
  <p>
    <h3>Qualification</h3>
    {{ jobDetails.qualification }}
  </p>
  <p><Link :href="`/apply/${jobDetails.id}`" class="btn btn-primary rounded-pill">Apply</Link></p>
</div>
<div class="col-md-2"></div>
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
import { Head, Link } from '@inertiajs/vue3';
import Layout from "../Shared/Layout.vue";
export default{
  components: { Layout, Head, Link },
  props: {
    jobDetails: Object,
  },
    methods: { },
  data(){
    return { }
  }
}
</script>