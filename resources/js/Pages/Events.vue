<template>
    <Head title="Happy Family - Events" />
    <Layout>
        <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Our team.jpeg');">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Events</h2>
				<ol class="breadcrumb header-bradcrumb">
				  <li><Link href="/">Home</Link></li>
				  <li class="mx-1">/</li>
				  <li class="active">Events</li>
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
					<h2>Upcoming Events</h2>
					<p>We are excited to share with you the upcoming events and activities that aim to make a positive impact in our community.</p>
					<p>Join us in creating meaningful change and be part of these inspiring initiatives:</p>
					<div class="border"></div>
				</div>
			</div>
			<!-- /section title -->

			<!-- Single Service Item -->
			<div v-for="event in upcomingEvents" :key="event.id" class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
				<div class="service-block p-2 text-center" :class="(event.id%2==0) ? 'color-bg' : ''">
					<div class="service-icon text-center">
						<img v-if="event.photo != null" :src="`/storage/${event.photo}`" class="w-100" />
					<div v-else class="text-center">No photo</div>
					</div>
					<h3>{{ event.event }}</h3>
					<p>
						<div><b>Date:</b> {{ event.date }} at {{ event.time }}</div>
						<div><b>Location:</b> {{ event.location }}</div>
					</p>
				</div>
			</div>
			<div v-if="upcomingEvents.length == 0"> <h4>There's no upcoming event at the moment!</h4></div>

		</div> <!-- End row -->

		<hr/>

		<div class="row no-gutters">
			<!-- section title -->
			<div class="col-12">
				<div class="title text-center">
					<h2>Passed Events</h2>
					<div class="border"></div>
				</div>
			</div>
			<!-- /section title -->

			<!-- Single Service Item -->
			<div v-for="event in passedEvents" :key="event.id" class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
				<Link :href="`/events/${event.event}`">
					<div class="service-block p-2 text-center" :class="(event.id%2==0) ? 'color-bg' : ''">
					<div class="service-icon text-center">
						<img v-if="event.photo != null" :src="`/storage/${event.photo}`" class="w-100" />
					<div v-else class="text-center">No photo</div>
					</div>
					<h3>{{ event.event }}</h3>
					<p>
						<div><b>Date:</b> {{ event.date }} at {{ event.time }}</div>
						<div><b>Location:</b> {{ event.location }}</div>
					</p>
				</div>
				</Link>
			</div>
		</div> 
		<div v-if="passedEvents.length == 0"> <h4>There's no passed event at the moment!</h4></div>
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
    //gallery: Object
    upcomingEvents: Object,
	passedEvents: Object
  },
    methods: { },
  data(){
    return { }
  }
}
</script>