<template>
    <Head title="Happy Family - Story" />
    <Layout>
        <section class="single-page-header" :style="{backgroundImage: `url('/storage/${story.photo}')`}">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>{{ story.heading }}</h2>
				<ol class="breadcrumb header-bradcrumb">
				  <li><Link href="/">Home</Link></li>
				  <li class="mx-1">/</li>
                  <li><Link href="/stories">Stories</Link></li>
				  <li class="mx-1">/</li>
				  <li class="active">{{ story.heading }}</li>
				</ol>
			</div>
		</div>
	</div>
</section>


	<!-- Start Services Section
		==================================== -->

<section class="services" id="services">
	<div class="container">
		<div class="row no-gutters">
			<!-- section title -->
			<div class="col-12">
				<div class="title text-center">
					<h2>{{ story.heading }}</h2>
					<div class="border"></div>
				</div>
			</div>
			<!-- /section title -->
			
            <div class="col-md-12">
                <h3>{{ story.heading }}</h3>
					<p>
						<div v-html="story.content"></div>
						<div><b>Date:</b> {{ story.created_at }}</div>
					</p>
            </div>

		</div> <!-- End row -->
	</div> <!-- End container -->
</section> 

    </Layout>
  </template>

<script>
// import Layout from './Layout'
import { Head, Link } from '@inertiajs/vue3';
import Layout from "../Shared/Layout.vue";

export default{
  components: { Layout, Head, Link },
  props: {
    story: Object
  },
    methods: { },
  data(){
    return { }
  }
}
</script>