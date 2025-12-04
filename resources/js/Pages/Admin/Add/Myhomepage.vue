<template>
    <Head title="HFRO - Home Page" />
   <Layout>
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Home Page</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Home Page</li>
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
                <h3 class="card-title">Home Page</h3>
  
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form @submit.prevent="save">
                  <div class="form-group">
                  <label for="heading">heading</label>
                  <input type="text" v-model="form.heading" id="heading" class="form-control" required />
                  <div v-if="form.errors.heading" v-text="form.errors.heading" class="text-danger"></div>
                </div>
                <div class="form-group">
                  <label for="link">link</label>
                  <input type="text" v-model="form.link" id="link" class="form-control" required />
                  <div v-if="form.errors.link" v-text="form.errors.link" class="text-danger"></div>
                </div>
                <div class="form-group">
                  <label for="caption">caption</label>
                  <textarea id="caption" v-model="form.caption" class="form-control" rows="4"></textarea>
                  <div v-if="form.errors.caption" v-text="form.errors.caption" class="text-danger"></div>
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
                <span v-if="!form.processing"><i  class="fa fa-plus"></i> Home Page</span> 
                <span v-else-if="form.processing"><div class="spinner-border spinner-border-sm" role="status"></div> Saving...</span>
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

<script setup>
import { defineComponent } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Layout from "../../../Shared/Admin/Layout.vue";
import Preloader from "../../../Shared/Admin/Preloader.vue";
defineProps({ categories: Object });
defineComponent({ Layout, Preloader, Head, Link });
let form = useForm({
    heading: null,
    caption: null,
    link: null,
    photo: null
});

let save = () =>{
    form.post('/admin/homepage');
};
</script>