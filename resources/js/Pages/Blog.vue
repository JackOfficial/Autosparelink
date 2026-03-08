<template>
    <Head title="Happy Family - Blog Detail" />
    <Layout>
        <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Our team.jpeg');">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>{{ blog.title }}</h2>
				<ol class="breadcrumb header-bradcrumb">
				  <li><Link href="/">Home</Link></li> <span class="mx-1">/</span>
				  <li class="active">{{ blog.title }}</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<!-- blog details part start -->
<section class="blog-details section">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <article class="post">
          <div class="post-image">
            <img class="img-fluid w-100" :src="`/storage/${blog.photo}`" :alt="blog.title">
          </div>
          <!-- Post Content -->
          <div class="post-content">
            <h3>{{ blog.title }}</h3>
            <ul class="list-inline">
              <li class="list-inline-item">
                <a href="#">{{blog.first_name}}</a>&nbsp;/
              </li>
              <li class="list-inline-item">
                <a href="#">{{ comments.length }} {{ (comments.length>1) ? 'comments' : 'comment' }}</a>&nbsp;/
              </li>
              <li class="list-inline-item">
                <a href="#">From {{ blog.blog_category }}</a>
              </li>
            </ul>
            <div v-html="blog.content"></div>
            <div class="mt-3">Share:</div>
            <!-- post share -->
            <ul class="post-content-share list-inline">
              <li class="list-inline-item"> 
                <a :href="`https://twitter.com/share?url=https://hfro.org/blog/${blog.title}`" target="__blank">
                  <i class="tf-ion-social-twitter"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a :href="`https://twitter.com/share?url=https://hfro.org/blog/${blog.title}`" target="__blank">
                  <i class="tf-ion-social-linkedin"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a :href="`https://www.facebook.com/sharer.php?u=https://hfro.org/blog/${blog.title}`" target="__blank">
                  <i class="tf-ion-social-facebook"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a :href="`mailto:?subject=${blog.title}&body=Check out this site: https://hfro.org/blog/${blog.title}`" target="__blank">
                  <i class="fa fa-envelope"></i>
                </a>
              </li>
            </ul>
            <h3>{{ comments.length }} {{ (comments.length>1) ? 'comments' : 'comment' }}</h3>
            <ul class="comment-list">
              <!-- comment list -->
              <li class="comment-list-item" v-for="comment in comments" :key="comment.id">
                <div class="comment-list-item-image">
                  <img v-if="comment.avatar == null" src="/Front/images/blog/comment-1.jpg" alt="comment-img">
                  <img v-else :src="comment.avatar" alt="">
                </div>
                <div class="comment-list-item-content">
                  <h5>{{ comment.name }}</h5>
                  <h6>{{ comment.created_at }}</h6>
                  <p>{{ comment.comment }}</p>
                  <div class="comment-btn" v-if="$page.props.user.name == comment.name">
                    <button @click="deleteComment(comment.id)" class="btn btn-outline-danger mr-2">Delete</button>
                    <button class="btn btn-outline-primary">Edit</button>
                  </div>
                  <a v-else href="#" class="comment-btn">reply</a>
                </div>
              </li>
            </ul>
            <div v-if="$page.props.user.login_status">
            <h3>Leave A Comments</h3>
            <!-- Comment Form -->
            <div v-if="$page.props.flash.message" class="alert alert-success">{{ $page.props.flash.message }}</div>
            <form class="comment-form" @submit.prevent="post">
              <div class="row">
                <div class="col-lg-6 col-md-6">
                  <input type="text" name="first-name" readonly :value="$page.props.user.name" class="form-control" id="first-name" placeholder="First Name" required>
                </div>
                <div class="col-lg-6 col-md-6">
                  <input type="email" name="mail" readonly :value="$page.props.user.email" class="form-control" id="mail" placeholder="Email" required>
                </div>
                <div class="col-lg-12 col-md-12">
                  <textarea class="form-control" v-model="form.comment" rows="2" placeholder="Type your comment..." required></textarea>
                  <div v-if="form.errors.comment" v-text="form.errors.comment" class="text-danger"></div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary" :disabled="form.processing"> 
                <span v-if="!form.processing"><i  class="fa fa-plus"></i> Post Comment</span> 
                <span v-else-if="form.processing"><div class="spinner-border spinner-border-sm" role="status"></div> Posting...</span>
            </button>
            </form>
            </div>
          </div>
        </article>
      </div>
      <div class="col-lg-4">
        <!-- sidebar -->
        <aside class="sidebar">
          <div class="widget-search widget">
            <form @submit.prevent="search">
              <!-- Search bar -->
              <input class="form-control" type="text" placeholder="Search..." v-model="searchText">
              <button type="submit" class="widget-search-btn">
                <i class="tf-ion-ios-search"></i>
              </button>
            </form>
          </div>
          <div class="widget-categories widget">
            <h2>Categories</h2>
            <!-- widget categories list -->
            <ul class="widget-categories-list">
              <li v-for="category in categories" :key="category.id">
                <Link :href="`/blogs/${category.id}`">{{ category.blog_category }}</Link>
              </li>
            </ul>
          </div>
          <div class="widget-post widget">
            <h2>Latest Post</h2>
            <!-- latest post -->
            <ul class="widget-post-list">
              <li v-for="latestBlog in latestBlogs" :key="latestBlog.id" class="widget-post-list-item">
                <div class="widget-post-image">
                  <Link :href="`/blog/${latestBlog.title}`">
                    <img :src="`/storage/${latestBlog.photo}`" :alt="latestBlog.title">
                  </Link>
                </div>
                <div class="widget-post-content">
                  <Link :href="`/blog/${latestBlog.title}`">
                    <h5>{{ latestBlog.title }}</h5>
                  </Link>
                  <h6>{{ latestBlog.created_at }}</h6>
                </div>
              </li>
            </ul>
          </div>
        </aside>
      </div>
    </div>
  </div>
</section>
    </Layout>
  </template>

<script>
// import Layout from './Layout'
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import Layout from "../Shared/Layout.vue";
export default{
  props: {
  blog: Object,
  latestBlogs: Object,
  categories: Object,
  comments: Object
},
components:{
  Layout, Head, Link
},
data(){
  return {
    searchText: '',
    preserveScroll: true
  }
},
methods:{
  deleteComment(id){
    preserveScroll:true
    router.post('/deleteComment/'+id);
  },
  search(){
    router.get('/blogs/search/'+this.searchText);
  }
},
setup(props){
  const form = useForm({
    comment: null,
    blogId: props.blog.id
});
const post = () =>{
    form.post('/comment');
};
return {form, post};
}
}
</script>