<template>
     <Head title="Admin - Users" />
    <Layout>
        <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users ({{ users.length }})</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
              <li class="breadcrumb-item active">Users </li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="user in users" :key="user.id">
                    <td>
                      <div v-if="user.photo == null">No photo</div>
                      <a v-else :href="user.photo" target="__blank">
                        <img :src="user.photo" alt="Photo" />
                      </a>
                    </td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                      <div v-if="user.roles == 0" class="badge badge-warning">Disactive</div>
                      <div v-if="user.roles == 1" class="badge badge-success">Active</div>
                      <div v-if="user.roles == 2" class="badge badge-danger">Banned</div>
                    </td>
                    <td>{{ user.created_at }}</td>
                    <td class="d-flex">
                    <Link class="btn btn-info btn-sm mr-2" :href="`/admin/users/${user.id}/edit`">
                          <i class="fas fa-pencil-alt"></i> Edit</Link>
                       <button @click="destroy(user.id)" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i> Delete</button>
                </td>
                  </tr>
                  <tr v-if="users.length==0">
                    <td colspan="6" class="text-center my-3">There's no user available at the moment.</td>
                </tr>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    </Layout>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3';
import Layout from "../../../Shared/Admin/Layout.vue";
import Preloader from "../../../Shared/Admin/Preloader.vue";
// defineProps({ name: Object })
export default{
  props: {},
  components: { Layout, Preloader, Head, Link },
  props:{
    users: Object,
  },
    methods: { },
  data(){
    return {}
  }
}
</script>