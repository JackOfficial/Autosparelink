<template>
    <Head title="HFRO - Blogs" />
<Layout>
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Blogs ({{ blogs.length }})</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
            <li class="breadcrumb-item active">Blogs</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div v-if="$page.props.flash.message" class="alert alert-success">{{ $page.props.flash.message }}</div>
<div class="card">
          <div class="card-header">
            <h3 class="card-title"><Link href="/admin/blogs/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Blog</Link></h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Photo</th>
                <th>Content</th>
                <th>Blogger</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(blog) in blogs" :key="blog.id">
                <td> {{ blog.id }}</td>
                <td>{{ blog.title }}</td>
                <td>{{ blog.blog_category }}</td>
                <td>
                  <a :href="`/storage/${blog.photo}`">
                  <img :src="`/storage/${blog.photo}`" class="img img-responsive img-thumbnail" style="width:100px;" />
                  </a>
                </td>
                <td v-html="blog.content"></td>
                <td>{{ blog.first_name }} {{ blog.last_name }}</td>
                <td>{{  blog.created_at }}</td>
                <td class="d-flex">
                    <Link class="btn btn-info btn-sm mr-2" :href="`/admin/blogs/${blog.id}/edit`">
                          <i class="fas fa-pencil-alt"></i> Edit</Link>
                       <button @click="destroy(blog.id)" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete</button>
                </td>
              </tr>
              <tr v-if="blogs.length==0">
                    <td colspan="8" class="text-center">There's no blog available at the moment.</td>
                </tr>
              </tbody>
              <tfoot>
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>Category</th>
                <th>Photo</th>
                <th>Content</th>
                <th>Blogger</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              </tfoot>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </section>
  <!-- /.content -->
</Layout>
</template>

<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import Layout from "../../../Shared/Admin/Layout.vue";
import Preloader from "../../../Shared/Admin/Preloader.vue";
import { defineComponent } from 'vue';
defineComponent({Layout, Preloader, Head, Link, useForm, router});
defineProps({blogs: Object})
const destroy = (id) => {
if(confirm("Are you sure you want to delete this blog?")){
     preserveScroll: true,
     router.delete('/admin/blogs/' + id)
  }
}
</script>