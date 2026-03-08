<template>
    <Head title="Happy Family - Event" />
    <Layout>
        <section class="single-page-header" :style="{backgroundImage: `url('/storage/${event.photo}')`}">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>{{ event.event }}</h2>
				<ol class="breadcrumb header-bradcrumb">
				  <li><Link href="/">Home</Link></li>
				  <li class="mx-1">/</li>
                  <li><Link href="/events">Events</Link></li>
				  <li class="mx-1">/</li>
				  <li class="active">{{ event.event }}</li>
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
					<h2>{{ event.event }}</h2>
					<div class="border"></div>
				</div>
			</div>
			<!-- /section title -->

			<!-- Single Service Item -->
			<div v-if="event.link != null" class="col-lg-12 col-sm-12 mb-12 mb-lg-12">
				<div class="service-block p-2 text-center">
					<div class="service-icon text-center">
						<div class="embed-responsive embed-responsive-16by9">
						<div v-html="event.link"></div>
				</div>
					</div>
				</div>
			</div>
            <div class="col-md-12">
                <h3>{{ event.event }}</h3>
					<p>
						<div v-html="event.description"></div>
						<div><b>Date:</b> {{ event.date }}</div>
						<div><b>Time:</b> {{ event.time }}</div>
						<div><b>Location:</b> {{ event.location }}</div>
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
    event: Object
  },
    methods: { },
  data(){
    return { }
  }
}
</script>