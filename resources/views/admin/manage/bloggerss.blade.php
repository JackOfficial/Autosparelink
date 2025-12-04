<template>
        <Head title="HFRO - Bloggers" />
   <Layout>
     <!-- Content Header (Page header) -->
     <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Bloggers ({{ bloggers.length }})</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Bloggers</li>
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
                <h3 class="card-title"><Link href="/admin/bloggers/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Blogger</Link></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Photo</th>
                    <th>Created at</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="(blogger) in bloggers" :key="blogger.id">
                    <td> {{ blogger.id }}</td>
                    <td>{{ blogger.first_name }} {{ blogger.last_name }}</td>
                    <td>{{ blogger.phone }}</td>
                    <td>{{ blogger.email }}</td>
                    <td><a :href="`/storage/${blogger.photo}`" target="__blank">
                            <img :src="`/storage/${blogger.photo}`" :alt="`${blogger.first_name} ${blogger.last_name}`" class="img img-responsive img-thumbnail" style="width:70px;" />
                          </a></td>
                    <td>{{  blogger.created_at }}</td>
                    <td class="d-flex">
                        <Link class="btn btn-info btn-sm mr-2" :href="`/admin/bloggers/${blogger.id}/edit`">
                              <i class="fas fa-pencil-alt"></i> Edit</Link>
                           <button @click="destroy(blogger.id)" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete</button>
                    </td>
                  </tr>
                  <tr v-if="bloggers.length==0">
                        <td colspan="7" class="text-center">There's no blogger available at the moment.</td>
                    </tr>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Photo</th>
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
defineProps({bloggers: Object})
const destroy = (id) => {
  if(confirm("Are you sure you want to delete this blogger?")){
         preserveScroll: true;
         router.delete('/admin/bloggers/' + id)
      }
}
</script>