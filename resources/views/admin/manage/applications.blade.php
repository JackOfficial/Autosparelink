<template>
  <Head title="HFRO - Careers" />
   <Layout>
     <!-- Content Header (Page header) -->
     <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>{{ postId==null ? 'Applications' : 'filtered' }} ({{ applications.length }}) 
             <div class="dropdown ">
  <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Export To Excel
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a :href="`/admin/letmesee/${post.id}`" class="dropdown-item" v-for="post in posts" :key="post.id">{{ post.title }}</a>
  </div>
</div>

            </h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Applications</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
  
      <!-- Main content -->
      <section class="content">
        <div v-if="form.id.length > 0" class="mb-2 d-flex">
          <button @click="shortlist()" class="btn btn-primary btn-sm mr-2">Shortlist</button>
          <button @click="hire()" class="btn btn-success btn-sm mr-2">Hire</button>
          <button @click="reject()" class="btn btn-success btn-sm">Reject</button>
        </div>
        <!-- Default box -->
        <div v-if="$page.props.flash.message" class="alert alert-success">{{ $page.props.flash.message }}</div>
        <div class="card">
          <div class="card-header">
            <div class="d-flex justify-content-between">
              <div class="card-title">Applications <span v-if="form.id.length>0">({{ form.id.length }})</span></div>
            <div class="d-flex">
            <div class="mr-3">
              <select @change="filterPost(postId)" v-model="postId" class="form-control form-control-sm">
               <option value="all">All</option>
               <option v-for="post in posts" :key="post.id"  :value="post.id">{{ post.title }}</option>
              </select>
            </div>
<div>
  <input type="text" v-model="keyword" @input="searchKeyword(keyword)" class="form-control form-control-sm" placeholder="Search..." />
</div>
              
            </div>
            </div>
        
          </div>
          <div class="card-body">
            <div class="table-responsive">
 <table id="example1" class="table table-striped projects">
                <thead>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Career</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Nationality</th>
                        <th>Level Of Education</th>
                        <th>Field Of Study</th>
                        <th>Notice Period</th>
                        <th>Desired Salary</th>
                        <th>resume</th>
                        <th>Cover Letter</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="application in applications" :key="application.id">
                      <td><input type="checkbox" :value="application.id" v-model="form.id" /></td>
                        <td>{{ application.id }}</td>
                        <td>{{ application.title }}</td>
                        <td>{{ application.first_name }} {{ application.last_name }}</td>
                        <td><a :href="`mailto:${application.email}`">{{ application.email }}</a></td>
                        <td>{{ application.phone }}</td>
                        <td>{{ application.address }}</td>
                        <td>{{ application.name }}</td>
                        <td>{{ application.level_of_education }}</td>
                        <td>{{ application.field_of_study }}</td>
                        <td>{{ application.notice_period }}</td>
                        <td>{{ application.desired_salary }}</td>
                        <td>
                          <a :href="`/storage/${application.resume}`" target="__blank">
                            <i class="far fa-file-pdf" style="font-size: 36px;"></i>
                          </a>
                        </td>
                        <td>
                          <a :href="`/storage/${application.cover_letter}`" target="__blank">
                            <i class="far fa-file-pdf" style="font-size: 36px;"></i>
                          </a>
                        </td>
                        <td class="project-state">
                            <span v-if="application.status == 0" class="badge badge-warning">Pending</span>
                            <span v-else-if="application.status == 1" class="badge badge-primary">Shortlisted</span>
                            <span v-else-if="application.status == 2" class="badge badge-success">Hired</span>
                            <span v-else class="badge badge-danger">Rejected</span>
                        </td>
                        <td>{{ application.created_at }}</td>
                        <td class="d-flex">
                            <Link class="btn btn-info btn-sm mr-2" :href="`/admin/applications/${application.id}`">
                              <i class="fas fa-pencil-alt"></i> Show</Link>
                           <button @click="destroy(application.id)" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                    <tr v-if="applications.length==0">
                        <td colspan="17" class="text-center">There's no application available at the moment.</td>
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
defineProps({applications: Object, ids: Object, posts: Object});

const destroy = (id) => {
  if(confirm("Are you sure you want to delete this application?")){
         preserveScroll: true,
         router.delete('/admin/applications/' + id)
      } 
};

const getpost = useForm({
    postId: null, 
       });

const searchText = useForm({
  keyword: null
});

const searchKeyword = (keyword) =>{
       searchText.get('/admin/applications/search/'+keyword);
      };

       const filterPost = (id) =>{
        form.get('/admin/applications/filter/'+id);
      };

const form = useForm({
          id: [],
       });

       const exports = useForm({});

      const shortlist = () =>{
        form.post('/admin/applications/shortlist');
      };

      const hire = () =>{
        form.post('/admin/applications/hire');
      };

      const reject = () =>{
        form.post('/admin/applications/reject');
      };

      const exportExcel = () =>{
//alert($id);
form.post('/admin/applications/export-selected');
       // exports.get('/export-excel');
        // if(form.id===null){
        //   alert("null")
        
        // }
        // else{
        //   alert("not null")
        //  // form.post('/admin/applications/export-selected');
        // }
        
      };

      // const selectAll = (ids) =>{
      //   form.id.append(ids)
      // };

</script>