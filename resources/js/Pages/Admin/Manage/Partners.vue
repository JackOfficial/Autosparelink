<template>
    <Head title="HFRO - Partners" />
<Layout>
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Partners ({{ partners.length }})</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
            <li class="breadcrumb-item active">Partners</li>
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
            <h3 class="card-title"><Link href="/admin/partners/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Partner</Link></h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>#</th>
                 <th>Logo</th>
                 <th>Partner</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="partner in partners" :key="partner.id">
                <td> {{ partner.id }}</td>
                <td>
                <a :href="`/storage/${partner.logo}`" target="__blank">
                <img :src="`/storage/${partner.logo}`" class="img img-responsive img-thumbnail" style="width:100px;"/>
              </a>  
              </td>
                <td><a :href="partner.link" target="__blank">{{ partner.partner }}</a></td>
                <td>{{ partner.created_at }}</td>
                <td class="d-flex">
                    <Link class="btn btn-info btn-sm mr-2" :href="`/admin/partners/${partner.id}/edit`">
                          <i class="fas fa-pencil-alt"></i> Edit</Link>
                       <button @click="destroy(partner.id)" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete</button>
                </td>
              </tr>
              <tr v-if="partners.length==0">
                    <td colspan="11" class="text-center my-3">There's no partner available at the moment.</td>
                </tr>
              </tbody>
              <tfoot>
              <tr>
                <th>#</th>
                <th>Logo</th>
                 <th>Partner</th>
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
defineProps({partners: Object});
const destroy = (id) => {
if(confirm("Are you sure you want to delete this partner?")){
     preserveScroll: true;
     router.delete('/admin/partners/' + id)
  }
}
</script>