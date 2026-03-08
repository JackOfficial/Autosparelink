<template>
    <Head title="HFRO - Careers" />
   <Layout>
     <!-- Content Header (Page header) -->
     <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Careers ({{ careers.length }})</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Careers</li>
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
            <div class="card-title"><Link href="/admin/careers/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Career</Link></div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
 <table id="example1" class="table table-striped projects">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Job type</th>
                        <th>Description</th>
                        <th>Qualification</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="career in careers" :key="career.id">
                        <td>{{ career.id }}</td>
                        <td>{{ career.title }}</td>
                        <td>{{ career.jobtype }}</td>
                        <td><span v-html="career.description.substr(0,100)"></span>...</td>
                        <td>{{ career.qualification }}</td>
                        <td>{{ career.deadline }}</td>
                        <td class="project-state">
                            <span v-if="career.status == 0" class="badge badge-warning">Disactive</span>
                            <span v-else class="badge badge-success">Active</span>
                        </td>
                        <td>{{ career.created_at }}</td>
                        <td class="d-flex">
                            <Link class="btn btn-info btn-sm mr-2" :href="`/admin/careers/${career.id}/edit`">
                              <i class="fas fa-pencil-alt"></i> Edit</Link>
                           <button @click="destroy(career.id)" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                    <tr v-if="careers.length==0">
                        <td colspan="8" class="text-center">There's no cause available at the moment.</td>
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
defineProps({careers: Object})
const destroy = (id) => {
  if(confirm("Are you sure you want to delete this career?")){
         preserveScroll: true,
         router.delete('/admin/careers/' + id)
      }
}
</script>