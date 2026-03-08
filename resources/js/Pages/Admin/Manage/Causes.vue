<template>
    <Head title="HFRO - Causes" />
   <Layout>
     <!-- Content Header (Page header) -->
     <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Causes ({{ causes.length }})</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Causes</li>
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
            <div class="card-title"><Link href="/admin/causes/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Cause</Link></div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
 <table id="example1" class="table table-striped projects">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Cause</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="cause in causes" :key="cause.id">
                        <td>
                            {{ cause.id }}
                        </td>
                        <td>
                          <a :href="`/storage/${cause.photo}`">
                            <img :src="`/storage/${cause.photo}`" class="img img-responsive img-thumbnail" style="width:100px;" />
                          </a>
                        </td>
                        <td>
                            <a>
                                {{ cause.cause }}
                            </a>
                            <br/>
                            <small>
                                {{ cause.created_at }}
                            </small>
                        </td>
                        <td>
                            {{ cause.description }}
                        </td>
                        <td class="project-state">
                            <span v-if="cause.status == 0" class="badge badge-warning">Disactive</span>
                            <span v-else class="badge badge-success">Active</span>
                        </td>
                        <td class="d-flex">
                            <Link class="btn btn-info btn-sm mr-2" :href="`/admin/causes/${cause.id}/edit`">
                              <i class="fas fa-pencil-alt"></i> Edit</Link>
                           <button @click="destroy(cause.id)" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                    <tr v-if="causes.length==0">
                        <td colspan="6" class="text-center">There's no cause available at the moment.</td>
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
defineProps({causes: Object})
const destroy = (id) => {
  if(confirm("Are you sure you want to delete this cause?")){
         preserveScroll: true,
         router.delete('/admin/causes/' + id)
      }
}
</script>