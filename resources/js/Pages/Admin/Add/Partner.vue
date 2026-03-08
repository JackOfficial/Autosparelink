<template>
    <Head title="HFRO - Add Partner" />
   <Layout>
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Add Partner</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Add Partner</li>
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
                <h3 class="card-title">Add Partner</h3>
  
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form @submit.prevent="save">

                    <div class="form-group">
                  <label for="partner">Partner</label>
                  <input type="text" v-model="form.partner" id="partner" class="form-control" required />
                  <div v-if="form.errors.partner" v-text="form.errors.partner" class="text-danger"></div>
                </div>

                    <div class="form-group">
                  <label for="logo">Logo</label>
                  <div>
                    <input type="file" @input="form.logo = $event.target.files[0]" id="logo" class="form-control" required />
                    <div v-if="form.errors.logo" v-text="form.errors.logo" class="text-danger"></div>
                   </div>
                </div>

                <div class="form-group">
                  <label for="link">Link</label>
                  <input type="text" v-model="form.link" id="link" class="form-control" required />
                 <div v-if="form.errors.link" v-text="form.errors.link" class="text-danger"></div>
                </div>
                <hr />
                <div class="form-group d-flex justify-content-between ">
            <a href="#" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary" :disabled="form.processing"> 
                <span v-if="!form.processing"><i  class="fa fa-plus"></i> Add Partner</span> 
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

defineComponent({ Layout, Preloader, Head, Link });

let form = useForm({
    partner: null,
    logo: null,
    link: null,
});

let save = () =>{
    form.post('/admin/partners');
};
</script>