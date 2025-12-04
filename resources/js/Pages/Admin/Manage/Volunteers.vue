<template>
    <Head title="HFRO - Careers" />
   <Layout>
     <!-- Content Header (Page header) -->
     <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Volunteers ({{ volunteers.length }})</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Volunteers</li>
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
            <div class="card-title">Volunteers</div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
 <table id="example1" class="table table-striped projects">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date Of Birth</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="volunteer in volunteers" :key="volunteer.id">
                        <td>{{ volunteer.id }}</td>
                        <td>{{ volunteer.name }}</td>
                        <td>{{ volunteer.dob }}</td>
                        <td>{{ volunteer.email }}</td>
                        <td>{{ volunteer.phone }}</td>
                        <td>{{ volunteer.reason }}</td>
                        <td class="project-state">
                            <span v-if="volunteer.status == 0" class="badge badge-warning">Disactive</span>
                            <span v-else class="badge badge-success">Active</span>
                        </td>
                        <td>{{ volunteer.created_at }}</td>
                        <td class="d-flex">
                            <Link class="btn btn-info btn-sm mr-2" :href="`/admin/volunteers/${volunteer.id}/edit`">
                              <i class="fas fa-pencil-alt"></i> Edit</Link>
                           <button @click="destroy(volunteer.id)" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                    <tr v-if="volunteers.length==0">
                        <td colspan="9" class="text-center">There's no volunteer available at the moment.</td>
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
defineProps({volunteers: Object})
const destroy = (id) => {
  if(confirm("Are you sure you want to delete this career?")){
         preserveScroll: true,
         router.delete('/admin/careers/' + id)
      }
}
</script>