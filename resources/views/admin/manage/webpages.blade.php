<template>
  <Head title="HFRO - Pages" />
<Layout>
<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Pages ({{ webpages.length }})</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
          <li class="breadcrumb-item active">Pages</li>
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
          <h3 class="card-title"><Link href="/admin/pages/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Page</Link></h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th>#</th>
               <th>Photo</th>
               <th>Page</th>
               <th>Header</th>
              <th>Text</th>
               <th>Link</th>
              <th>Created at</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="page in webpages" :key="page.id">
              <td>{{ page.id }}</td>
              <td>
              <Link :href="`/storage/${page.photo}`" target="__blank">
              <img :src="`/storage/${page.photo}`" class="img img-responsive img-thumbnail" style="width:100px;"/>
              </Link>  
              </td>
               <td>{{ page.page_name }}</td>
              <td>{{ page.header }}</td>
              <td>{{ page.text }}</td>
              <td><a :href="page.link" target="__blank">{{ page.link }}</a></td>
              <td>{{ page.created_at }}</td>
              <td class="d-flex">
                  <Link class="btn btn-info btn-sm mr-2" :href="`/admin/pages/${page.id}/edit`">
                    <i class="fas fa-pencil-alt"></i> Edit</Link>
              </td>
            </tr>
            <tr v-if="webpages.length==0">
                  <td colspan="7" class="text-center my-3">There's no page description.</td>
              </tr>
            </tbody>
            <tfoot>
            <tr>
              <th>#</th>
               <th>Photo</th>
                 <th>Page</th>
               <th>Header</th>
               <th>Text</th>
               <th>Link</th>
              <th>Created at</th>
              <th>Action</th>
            </tr>
            </tfoot>
          </table>
        </div>
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
defineProps({webpages: Object});
const destroy = (id) => {
if(confirm("Are you sure you want to delete this page?")){
   preserveScroll: true;
   router.delete('/admin/pages/' + id)
}
}
</script>