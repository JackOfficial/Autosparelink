<template>
    <Head title="HFRO - Events" />
   <Layout>
     <!-- Content Header (Page header) -->
     <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Events ({{ events.length }})</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Events</li>
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
            <div class="card-title"><Link href="/admin/events/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> Add Event</Link></div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
 <table id="example1" class="table table-striped projects">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event</th>
                        <th>Photo</th>
                        <th>Video</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Created At</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="event in events" :key="event.id">
                        <td>
                            {{ event.id }}                       
                        </td>
                        <td>
                            {{ event.event }}                       
                        </td>
                        <td>
                            <a :href="`/storage/${event.photo}`" target="__blank" v-if="event.photo != null">
                            <img :src="`/storage/${event.photo}`" alt="event photo" class="img img-responsive img-thumbnail" style="width:100px; height: auto;" />
                            </a>
                            <div v-else>No photo</div>
                        </td>
                        <td>
                            <a href="#" target="__blank" v-if="event.link != null">
                              {{ event.link }}
                            </a>
                            <div v-else>No Link provided</div>
                        </td>
                        <td>
                           <div v-if="event.description != null" v-html="event.description.substr(0,250)"></div> 
                           <div v-else>No description</div> 
                        </td>
                        <td>
                          <div v-if="event.date != null || event.date != ''">{{ event.date }}</div>
                          <div v-else-if="event.date == ''">No date set</div>
                        </td>
                        <td>
                          <div v-if="event.location != null">{{ event.location }}</div>
                          <div v-else>No location mentioned</div>
                        </td>
                        <td>
                            {{ event.created_at }}
                        </td>
                        <td>
                          <span v-if="event.status == 0" class="badge badge-warning">Disactive</span>
                            <span v-else class="badge badge-success">Active</span>
                        </td>
                        <td class="d-flex">
                            <Link class="btn btn-info btn-sm mr-2" :href="`/admin/events/${event.id}/edit`">
                              <i class="fas fa-pencil-alt"></i> Edit</Link>
                           <button @click="destroy(event.id)" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete</button>
                        </td>
                    </tr>
                    <tr v-if="events.length==0">
                        <td colspan="8" class="text-center">There's no event available.</td>
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
defineProps({events: Object})
const destroy = (id) => {
  if(confirm("Are you sure you want to delete this event?")){
         preserveScroll: true,
         router.delete('/admin/events/' + id)
      }
}
</script>