<template>
    <Head title="Happy Family - Project details" />
    <Layout>
        <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Our team.jpeg');">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Project Details</h2>
				<ol class="breadcrumb header-bradcrumb">
				  <li><a href="/">Home</a></li>
          <li class="mx-1">/</li>
				  <li class="active">Project details</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="section container">

  <div class="title text-center">
				<h2>Project</h2>
				<p>At Happy Family Rwanda Organization (HFRO), we are passionate about addressing the challenges faced by underprivileged communities around the world. Through our various programs and initiatives, we strive to make a positive impact in the lives of individuals and promote sustainable change.</p>
				<div class="border"></div>
			</div>
      <h1>{{ project.project }}</h1>
 <div class="row">
<div class="col-md-4">
  <img :src="`/storage/${project.photo}`" class="img img-responsive w-100" :alt="project.project">
</div>
<div class="col-md-8" v-html="project.description"></div>
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
    project: Object,
  },
    methods: { },
  data(){
    return { }
  }
}
</script>