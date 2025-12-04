<template>
    <Head title="Happy Family - Blogs" />
    <Layout>
        <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Our team.jpeg');">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Blogs</h2>
				<ol class="breadcrumb header-bradcrumb">
				  <li><Link href="/">Home</Link></li>
				  <li class="mx-1">/</li>
				  <li class="active">Blogs</li>
				</ol>
			</div>
		</div>
	</div>
</section>



<section class="posts section">
	<div class="container">
		<div class="row">
			<!-- /section title -->
			<!-- single blog post -->
			<article v-for="blog in blogs.data" :key="blog.id" class="col-md-4 col-sm-6 col-xs-12 clearfix ">
				<div class="post-item">
					<div class="media-wrapper">
						<img :src="`/storage/${blog.photo}`" :alt="blog.title" class="img-fluid">
					</div>

					<div class="content">
						<h3><Link :href="`/blog/${blog.title}`">{{ blog.title }}</Link></h3>
						<div v-html="blog.content.substr(0,250)"></div>
						<div>...</div>
						<Link class="btn btn-main" :href="`/blog/${blog.title}`">Read more</Link>
					</div>
				</div>
			</article>
		</div>
		<div class="my-3 text-center" v-if="blogs.data.length == 0">
			<h3 v-if="$page.url.startsWith('/blogs/search')">No blog found for {{ keyword }}!</h3>
			<h3 v-else>No blog found</h3>
		</div>
		<Pagination v-if="blogs.length >= 6" :links="blogs.links" class="mt-5" />
	</div>
</section>
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
    blogs: Object,
	keyword: String
  },
    methods: { },
  data(){
    return { }
  }
}
</script>