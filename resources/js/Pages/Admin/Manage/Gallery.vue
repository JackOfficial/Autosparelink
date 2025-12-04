<template>
    <Head title="HFRO - Gallery" />
   <Layout>
     <!-- Content Header (Page header) -->
     <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Photos ({{ gallery.length }})</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Gallery</li>
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
            <div class="card-title"><Link href="/admin/gallery/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Gallery</Link></div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
 <table id="example1" class="table table-striped projects">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Description</th>
                        <th>Created At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="photo in gallery" :key="photo.id">
                        <td>
                            {{ photo.id }}                       
                        </td>
                        <td>
                            <a :href="`/storage/${photo.photo}`" target="__blank">
                            <img :src="`/storage/${photo.photo}`" alt="photo" class="img img-responsive img-thumbnail" style="width:100px; height: auto;" />
                            </a>
                        </td>
                        <td>
                            {{ photo.description }}
                        </td>
                        <td>
                            {{ photo.created_at }}
                        </td>
                        <td class="d-flex">
                            <Link class="btn btn-info btn-sm mr-2" :href="`/admin/gallery/${photo.id}/edit`">
                              <i class="fas fa-pencil-alt"></i> Edit</Link>
                           <button @click="destroy(photo.id)" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                    <tr v-if="gallery.length==0">
                        <td colspan="4" class="text-center">There's no photo available in the gallery.</td>
                    </tr>
                </tbody>
            </table>
          </div>
          </div>
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
defineProps({gallery: Object})
const destroy = (id) => {
  if(confirm("Are you sure you want to delete this photo?")){
         preserveScroll: true,
         router.delete('/admin/gallery/' + id)
      }
}
</script>