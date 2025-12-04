<template>
    <Head title="HFRO - Add Event" />
   <Layout>
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Add Event</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Add Event</li>
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
                <h3 class="card-title">Add Event</h3>
  
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form @submit.prevent="save">
                    <div class="form-group">
                  <label for="event">Event*</label>
                  <input type="text" v-model="form.event" id="event" class="form-control" required />
                  <div v-if="form.errors.event" v-text="form.errors.event" class="text-danger"></div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="link">Youtube Link</label>
                  <input type="link" v-model="form.link" id="link" class="form-control" />
                  <div v-if="form.errors.link" v-text="form.errors.link" class="text-danger"></div>
                         </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                  <label for="photo">Photo</label>
                  <div>
                    <input type="file" @input="form.photo = $event.target.files[0]" accept="image/*" id="photo" class="" />
                    <div v-if="form.errors.photo" v-text="form.errors.photo" class="text-danger"></div>
                  </div>
                </div>
                    </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                  <label for="location">Location*</label>
                  <input type="text" v-model="form.location" id="location" class="form-control" required />
                  <div v-if="form.errors.location" v-text="form.errors.location" class="text-danger"></div>
                </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-md-6">
                         <div class="form-group">
                          <label for="date">Date*</label>
                  <input type="date" v-model="form.date" id="date" class="form-control" required />
                  <div v-if="form.errors.date" v-text="form.errors.date" class="text-danger"></div>
                         </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="time">Time*</label>
                  <input type="time" v-model="form.time" id="time" class="form-control" required />
                  <div v-if="form.errors.time" v-text="form.errors.time" class="text-danger"></div>
                          </div>
                        </div>
                      </div>
                 
                </div>
                  </div>
                  
                </div>

                <div class="form-group">
                  <label for="description">Event Description</label>
                  <Editor
      api-key="1nvwqlfylv7o7jo5qyh0s0emj2uo3ss2ytoiqzh33l2e0pls"
      v-model="form.description"
      :init="{
        plugins: 'lists link image table code help wordcount'
      }"
    />
    <div v-if="form.errors.description" v-text="form.errors.description" class="text-danger"></div>
                </div> 
                <hr />
                <div class="form-group">
            <button type="submit" class="btn btn-primary" :disabled="form.processing"> 
                <span v-if="!form.processing"><i  class="fa fa-plus"></i> Add Event</span> 
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

<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Layout from "../../../Shared/Admin/Layout.vue";
import Preloader from "../../../Shared/Admin/Preloader.vue";
import Editor from '@tinymce/tinymce-vue'

export default{
  components: {
  Layout, Preloader, Head, Link, Editor
    },
    data() {
            return {
                
            };
        },
        setup(){
        const form = useForm({
    event: null,
    photo: null,
    link: null,
    description: null, 
    date: null, 
    time: null,
    location: null, 
});

      const save = () =>{
    form.post('/admin/events');
};

      return {form, save};
    }
}
</script>