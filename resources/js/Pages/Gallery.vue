<template>
    <Head title="Happy Family - Our Gallery" />
    <Layout>
        <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Our team.jpeg');">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Gallery</h2>
				<ol class="breadcrumb header-bradcrumb">
				  <li><Link href="/">Home</Link></li>
				  <li class="mx-1">/</li>
				  <li class="active">Gallery</li>
				</ol>
			</div>
		</div>
	</div>
</section>


	<!-- Start Services Section
		==================================== -->

<section class="services d-none" id="services">
	<div class="container">
		<div class="row no-gutters">
			<!-- section title -->
			<div class="col-12">
				<div class="title text-center">
					<h2>Our Services</h2>
					<p>Vestibulum nisl tortor, consectetur quis imperdiet bibendum, laoreet sed arcu. Sed condimentum iaculis ex,
						in faucibus lorem accumsan non. Donec mattis tincidunt metus. Morbi sed tortor a risus luctus dignissim.</p>
					<div class="border"></div>
				</div>
			</div>
			<!-- /section title -->

			<!-- Single Service Item -->
			<div class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
				<div class="service-block p-4 color-bg text-center">
					<div class="service-icon text-center">
						<i class="tf-ion-ios-copy-outline"></i>
					</div>
					<h3>WordPress Theme</h3>
					<p>Lorem ipsum dolor sit amet, consectetur.. Sed id lorem eget orci dictum facilisis vel id tellus. Nullam
						iaculis arcu at mauris dapibus consectetur.</p>
				</div>
			</div>
			<!-- End Single Service Item -->

			<!-- Single Service Item -->
			<div class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
				<div class="service-block p-4 text-center">
					<div class="service-icon text-center">
						<i class="tf-ion-ios-alarm-outline"></i>
					</div>
					<h3>Responsive Design</h3>
					<p>Lorem ipsum dolor sit amet, consectetur.. Sed id lorem eget orci dictum facilisis vel id tellus. Nullam
						iaculis arcu at mauris dapibus consectetur.</p>
				</div>
			</div>
			<!-- End Single Service Item -->

			<!-- Single Service Item -->
			<div class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
				<div class="service-block p-4 color-bg text-center">
					<div class="service-icon text-center">
						<i class="tf-ion-ios-book-outline"></i>
					</div>
					<h3>Media &amp; Advertisement</h3>
					<p>Lorem ipsum dolor sit amet, consectetur.. Sed id lorem eget orci dictum facilisis vel id tellus. Nullam
						iaculis arcu at mauris dapibus consectetur.</p>
				</div>
			</div>
			<!-- End Single Service Item -->

			<!-- Single Service Item -->
			<div class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
				<div class="service-block p-4  text-center">
					<div class="service-icon text-center">
						<i class="tf-ion-ios-briefcase-outline"></i>
					</div>
					<h3>Graphic Design</h3>
					<p>Lorem ipsum dolor sit amet, consectetur.. Sed id lorem eget orci dictum facilisis vel id tellus. Nullam
						iaculis arcu at mauris dapibus consectetur.</p>
				</div>
			</div>
			<!-- End Single Service Item -->

			<!-- Single Service Item -->
			<div class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
				<div class="service-block p-4 color-bg text-center">
					<div class="service-icon text-center">
						<i class="tf-ion-ios-crop"></i>
					</div>
					<h3>Apps Development</h3>
					<p>Lorem ipsum dolor sit amet, consectetur.. Sed id lorem eget orci dictum facilisis vel id tellus. Nullam
						iaculis arcu at mauris dapibus consectetur.</p>
				</div>
			</div>
			<!-- End Single Service Item -->

			<!-- Single Service Item -->
			<div class="col-lg-4 col-sm-6 mb-4 mb-lg-0">
				<div class="service-block p-4 text-center">
					<div class="service-icon text-center">
						<i class="tf-ion-ios-home-outline"></i>
					</div>
					<h3>Networking</h3>
					<p>Lorem ipsum dolor sit amet, consectetur.. Sed id lorem eget orci dictum facilisis vel id tellus. Nullam
						iaculis arcu at mauris dapibus consectetur.</p>
				</div>
			</div>
			<!-- End Single Service Item -->

		</div> <!-- End row -->
	</div> <!-- End container -->
</section> 

		<section class="portfolio section">
	<div class="container">
		<div class="row ">
			<div class="col-lg-12">

				<!-- section title -->
				<div class="title text-center">
					<h2>Our Gallery</h2>
					<p>We share the same passion.</p>
					<div class="border"></div>
				</div>
				<!-- /section title -->

				<div class="portfolio-filter d-none">
					<button type="button" data-filter="all">All</button>
					<button type="button" data-filter="photography">Photography</button>
					<button type="button" data-filter="ios">IOS App</button>
					<button type="button" data-filter="development">Development</button>
					<button type="button" data-filter="design">Design</button>
				</div>

				<div class="row filtr-container">
					<div class="col-md-3 col-sm-6 col-xs-6 filtr-item" v-for="photo in gallery" :key="photo.id" data-category="mix, design">
						<div class="portfolio-block">
							<img class="img-fluid" :src="`/storage/${photo.photo}`" alt="">
							<div class="caption">
								<a class="search-icon" :href="`/storage/${photo.photo}`" data-lightbox="image-1">
									<i class="tf-ion-ios-search-strong"></i>
								</a>
								<h4><a href="">{{ photo.description }}</a></h4>
							</div>
						</div>
					</div>
				</div>
			</div> <!-- /end col-lg-12 -->
		</div> <!-- end row -->
	</div> <!-- end container -->
</section> <!-- End section -->

    </Layout>
  </template>

<script>
// import Layout from './Layout'
import { Head, Link } from '@inertiajs/vue3';
import Layout from "../Shared/Layout.vue";

export default{
  components: { Layout, Head, Link },
  props: {
    gallery: Object
  },
    methods: { },
  data(){
    return { }
  }
}
</script>