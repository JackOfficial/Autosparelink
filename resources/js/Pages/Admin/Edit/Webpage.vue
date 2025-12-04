<template>
    <Head title="HFRO - Edit Page" />
   <Layout>
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Edit Page</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Edit Page</li>
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
                <h3 class="card-title">Add Page</h3>
  
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
                  <label for="page">Page</label>
                  <input type="text" placeholder="Enter the page name" v-model="form.page_name" id="page" class="form-control" required />
                  <div v-if="form.errors.page_name" v-text="form.errors.page_name" class="text-danger"></div>
                </div>
                   </div>
                   <div class="col-md-6">
                    <div class="form-group">
                  <label for="title">Header</label>
                  <input type="text" v-model="form.title" id="title" class="form-control" required />
                  <div v-if="form.errors.title" v-text="form.errors.title" class="text-danger"></div>
                </div>
                  </div>
                 </div>
                <div class="form-group">
                  <label for="text">Text</label>
                  <textarea id="text" v-model="form.text" class="form-control" rows="2"></textarea>
                  <div v-if="form.errors.text" v-text="form.errors.text" class="text-danger"></div>
                </div>
                <div class="form-group">
                  <label for="link">Link</label>
                  <input id="link" placeholder="/home" v-model="form.link" class="form-control" />
                  <div v-if="form.errors.link" v-text="form.errors.link" class="text-danger"></div>
                </div>
                <div class="form-group">
                  <label for="photo">Photo</label>
                  <div>
                    <input type="file" @input="form.photo = $event.target.files[0]" id="photo" class="" />
                    <div v-if="form.errors.photo" v-text="form.errors.photo" class="text-danger"></div>
                    <div>
                      <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                      {{ form.progress.percentage }}%
                    </progress>
                    </div>
                  </div>
                </div>
                
                <hr />
                <div class="form-group d-flex justify-content-between ">
            <a href="#" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary" :disabled="form.processing"> 
                <span v-if="!form.processing"><i  class="fa fa-edit"></i> Page</span> 
                <span v-else-if="form.processing"><div class="spinner-border spinner-border-sm" role="status"></div> Saving...</span>
            </button>
          </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-md-4">
            <img :src="`/storage/${page.photo}`" class="w-100" />
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
  props: {
     page: Object
    }, 
    setup(props){
        let form = useForm({
            id: props.page.id, 
            page_name: props.page.page_name, 
            title: props.page.header, 
            text: props.page.text,
            link: props.page.link,
            photo: props.page.photo,
            _method: 'put',
     });

let update = (id) =>{
    form.post('/admin/pages/'+id);
};
return {form, update}
    }
}
</script>