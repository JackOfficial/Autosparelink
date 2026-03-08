<template>
    <Head title="HFRO - Add Blogger" />
   <Layout>
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Add Person</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Add Person</li>
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
                <h3 class="card-title">Add Person</h3>
  
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form @submit.prevent="save">
                    <div class="form-group">
                    <label for="photo">Photo</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" @input="form.photo = $event.target.files[0]" class="custom-file-input" id="photo" required>
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                    </div>
                    <div v-if="form.errors.photo" v-text="form.errors.photo" class="text-danger"></div>
                    <div>
                      <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                      {{ form.progress.percentage }}%
                    </progress>
                    </div>
                  </div>
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" v-model="form.name" id="name" placeholder="Enter your full name" class="form-control" required />
                  <div v-if="form.errors.name" v-text="form.errors.name" class="text-danger"></div>
                </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                  <label for="title">Title</label>
                  <input type="text" v-model="form.title" id="title" placeholder="Enter Your Title" class="form-control" required />
                  <div v-if="form.errors.title" v-text="form.errors.title" class="text-danger"></div>
                </div>    
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                     <label for="phone">Phone</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    </div>
                    <input type="text" id="phone" class="form-control" v-model="form.phone" placeholder="Enter your phone" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                  </div>
                  <div v-if="form.errors.phone" v-text="form.errors.phone" class="text-danger"></div>
                </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                        <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="email" v-model="form.email" id="email" placeholder="Enter your email" class="form-control" />
                  </div>
                  <div v-if="form.errors.email" v-text="form.errors.email" class="text-danger"></div>
                </div>
            </div>
            </div>
                
                <div class="form-group">
                  <label for="bio">bio</label>
                  <div>
                    <textarea v-model="form.bio" id="bio" rows="2" placeholder="Enter your bio" class="form-control"></textarea>
                    <div v-if="form.errors.bio" v-text="form.errors.bio" class="text-danger"></div>
                  </div>
                </div>

                <fieldset>
                <legend>Socialmedia</legend>
                <div class="form-group">
                  <label for="facebook">Facebook</label>
                  <div>
                    <input type="text" v-model="form.facebook" id="twitter" placeholder="Enter your Facebook" class="form-control" />
                    <div v-if="form.errors.facebook" v-text="form.errors.facebook" class="text-danger"></div>
                  </div>
                </div>
              
                <div class="form-group">
                  <label for="socialMedia">LinkedIn</label>
                  <div>
                    <input type="text" v-model="form.linkedin" id="linkedin" placeholder="Enter your linkedin" class="form-control" />
                    <div v-if="form.errors.linkedin" v-text="form.errors.linkedin" class="text-danger"></div>
                  </div>
                </div>
                <div class="form-group">
                  <label for="twitter">Twitter (X)</label>
                  <div>
                    <input type="text" v-model="form.twitter" id="twitter" placeholder="Enter your twitter" class="form-control" />
                    <div v-if="form.errors.twitter" v-text="form.errors.twitter" class="text-danger"></div>
                  </div>
                </div>
              </fieldset>

                <hr />
                <div class="form-group">
            <button type="submit" class="btn btn-primary" :disabled="form.processing"> 
                <span v-if="!form.processing"><i  class="fa fa-plus"></i> Add Person</span> 
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
import { defineComponent, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Layout from "../../../Shared/Admin/Layout.vue";
import Preloader from "../../../Shared/Admin/Preloader.vue";
defineProps({});

defineComponent({ Layout, Preloader, Head, Link });
let form = useForm({
    photo: null,
    name: null, 
    title: null,
    phone: null,
    email: null,
    facebook: null,
    linkedin: null,
    twitter: null,
    bio: null,
});

let save = () =>{
    form.post('/admin/team');
};
</script>