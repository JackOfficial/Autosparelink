<template>
    <Head title="HFRO - Team" />
<Layout>
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Team ({{ team.length }})</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
            <li class="breadcrumb-item active">Team</li>
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
            <h3 class="card-title"><Link href="/admin/team/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Blogger</Link></h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
              <tr>
                <th>#</th>
                 <th>Photo</th> 
                 <th>Name</th>
                 <th>Title</th>
                 <th>Phone</th>
                 <th>Email</th>
                 <th>Bio</th>
                 <th>Social Media</th>
                 <th>Status</th>
                <th>Created at</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="person in team" :key="person.id">
                <td> {{ person.id }}</td>
                <td>
                <a :href="`/storage/${person.photo}`" target="__blank">
                <img :src="`/storage/${person.photo}`" class="img img-responsive img-thumbnail" style="width:100px;"/>
              </a>  
              </td>
                <td>{{ person.name }}</td>
                <td>{{ person.title }}</td>
                <td>{{ person.phone }}</td>
                <td>{{ person.email }}</td>
                <td>
                  {{ person.bio }} 
                </td>
                <td>
                 <a v-if="person.facebook != null" :href="person.facebook" target="__blank">Facebook</a><br />
                 <a v-if="person.linkedin != null" :href="person.linkedin" target="__blank">LinkedIn</a><br />
                 <a v-if="person.twitter != null" :href="person.twitter" target="__blank">Twitter</a>
                 <span v-else>No Socialmedia to display</span>
                </td>
                <td>
                  <span v-if="person.status == 1" class="badge badge-primary">Active</span>
                  <span v-if="person.status == 0" class="badge badge-primary">Disactive</span>
                </td>
                <td>{{ person.created_at }}</td>
                <td class="d-flex">
                    <Link class="btn btn-info btn-sm mr-2" :href="`/admin/team/${person.id}/edit`">
                          <i class="fas fa-pencil-alt"></i> Edit</Link>
                       <button @click="destroy(person.id)" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete</button>
                </td>
              </tr>
              <tr v-if="team.length==0">
                    <td colspan="11" class="text-center my-3">There's no person available at the moment.</td>
                </tr>
              </tbody>
              <tfoot>
              <tr>
                <th>#</th>
                 <th>Photo</th> 
                 <th>Name</th>
                 <th>Title</th>
                 <th>Phone</th>
                 <th>Email</th>
                 <th>Bio</th>
                 <th>Social Media</th>
                 <th>Status</th>
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
defineProps({team: Object})
const destroy = (id) => {
if(confirm("Are you sure you want to delete this person?")){
     preserveScroll: true;
     router.delete('/admin/team/' + id)
  }
}
</script>