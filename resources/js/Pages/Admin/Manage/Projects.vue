<template>
    <Head title="HFRO - Projects" />
   <Layout>
     <!-- Content Header (Page header) -->
     <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>{{ projects.length }} {{ (projects.length <= 0) ? 'Project' : 'Projects' }}</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Projects</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
  
      <!-- Main content -->
      <section class="content">
  
        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><Link href="/admin/projects/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Project</Link></h3>
  
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cause</th>
                        <th>Project</th>
                        <th>photo</th>
                        <th>Budget</th>
                        <th>Period</th>
                         <th>Progress</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="project in projects" :key="project.id">
                        <td>{{ project.id }}</td>
                         <td>{{ project.cause }}</td>
                        <td><a>{{ project.project }}</a>
                            <br/>
                            <small>{{ project.created_at }}</small>
                        </td>
                        <td>
                            <a :href="`/storage/${project.photo}`" target="__blank">
                                <img :alt="project.project" class="img img-responsive img-thumbnail" style="width: 50px; height: auto;" :src="`/storage/${project.photo}`">
                            </a>
                        </td>
                        <td>{{ project.budget }}</td>
                        <td>{{ project.period }}</td>
                        <td class="project_progress">
                            <div class="progress progress-sm">
                                <div class="progress-bar bg-green" role="progressbar" :aria-valuenow="project.progress" aria-valuemin="0" aria-valuemax="100" style="width: 57%">
                                </div>
                            </div>
                            <small>
                                {{ project.progress }}% {{ (project.progress == 100) ? 'Complete' : '' }} 
                            </small>
                        </td>
                        <td class="project-state">
                            <span v-if="project.progress == 100" class="badge badge-success">Success</span>
                            <span v-else class="badge badge-warning">On Going</span>
                        </td>
                        <td class="d-flex">
                            <Link class="btn btn-info btn-sm mr-2" :href="`/admin/projects/${project.id}/edit`">
                                <i class="fas fa-pencil-alt"></i> Edit</Link>
                            <button class="btn btn-danger btn-sm" @click="destroy(project.id)">
                                <i class="fas fa-trash"></i> Delete </button>
                        </td>
                    </tr>

                    <tr v-if="projects.length == 0">
                        <td colspan="8" class="text-center py-3">
                            No Project available at the moment 
                            <br><Link href="/admin/projects/create">Add Project</Link></td>
                    </tr>
                </tbody>
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
defineProps({projects: Object});
const destroy = (id) => {
  if(confirm("Are you sure you want to delete this project?")){
         preserveScroll: true,
         router.delete('/admin/projects/' + id)
      }
}
</script>