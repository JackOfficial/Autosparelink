<template>
    <Head title="HFRO - Edit Blogger" />
   <Layout>
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Edit Blogger</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Edit Blogger</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
  
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-8">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Edit Blogger</h3>
  
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form @submit.prevent="update(form.id)">
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                  <label for="firstName">First Name</label>
                  <input type="text" v-model="form.firstName" id="firstName" placeholder="Enter first name" class="form-control" required />
                  <div v-if="form.errors.firstName" v-text="form.errors.firstName" class="text-danger"></div>
                </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                  <label for="lastName">Last Name</label>
                  <input type="text" v-model="form.lastName" id="lastName" placeholder="Enter last name" class="form-control" required />
                  <div v-if="form.errors.lastName" v-text="form.errors.lastName" class="text-danger"></div>
                </div>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                  <label for="phone">Phone</label>
                  <input type="text" id="phone" v-model="form.phone" placeholder="Enter your phone" class="form-control" />
                  <div v-if="form.errors.phone" v-text="form.errors.phone" class="text-danger"></div>
                </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" v-model="form.email" id="email" placeholder="Enter your email" class="form-control" />
                  <div v-if="form.errors.email" v-text="form.errors.email" class="text-danger"></div>
                </div>
                    </div>
                </div>
                
                <div class="form-group">
                  <label for="photo">Photo</label>
                  <div>
                    <input type="file" @input="form.photo = $event.target.files[0]" id="photo" class="" />
                    <div v-if="form.errors.photo" v-text="form.errors.photo" class="text-danger"></div>
                  </div>
                </div>
                <hr />
                <div class="form-group">
            <button type="submit" class="btn btn-primary" :disabled="form.processing"> 
                <span v-if="!form.processing"><i  class="fa fa-plus"></i> Edit Blogger</span> 
                <span v-else-if="form.processing"><div class="spinner-border spinner-border-sm" role="status"></div> Updating...</span>
            </button>
          </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
      </section>
   </Layout>
</template>

<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Layout from "../../../Shared/Admin/Layout.vue";
import Preloader from "../../../Shared/Admin/Preloader.vue";

export default{
    components:{
        Layout, Preloader, Head, Link
    },
    props:{
        blogger:Object
    },
    setup(props){
        const form = useForm({
          id: props.blogger.id,
          firstName: props.blogger.first_name, 
          lastName: props.blogger.last_name,
          phone: props.blogger.phone,
          email: props.blogger.email,
          photo: null,
          _method: 'put'
         });
         const update = (id) =>{
          form.post('/admin/bloggers/'+id);
     };
        return {form, update};
    }
}
</script>