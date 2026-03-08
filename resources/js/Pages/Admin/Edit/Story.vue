<template>
    <Head title="HFRO - Post Story" />
   <Layout>
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Post Story</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Post Story</li>
              </ol>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
  
      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Post Story</h3>
  
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
                  <label for="blogger">Blogger</label>
                  <select v-model="form.blogger" id="blogger" class="form-control">
                   <option :selected="form.blogger === blogger.id" v-for="blogger in bloggers" :value="blogger.id" :key="blogger.id">{{ blogger.first_name }} {{ blogger.last_name }}</option>
                  </select>
                 <div v-if="form.errors.blogger" v-text="form.errors.blogger" class="text-danger"></div>
                </div>
                    </div>
                </div>
                <div class="row">
                 <div class="col-md-6">
                    <div class="form-group">
                  <label for="title">Title</label>
                  <input type="text" v-model="form.title" id="title" placeholder="Enter blog title" class="form-control" required />
                  <div v-if="form.errors.title" v-text="form.errors.title" class="text-danger"></div>
                </div>
                 </div>
                 <div class="col-md-6">
                    <div class="form-group">
                  <label for="photo">Photo</label>
                  <div>
                    <input type="file" @input="form.photo = $event.target.files[0]" id="photo" accept="image/*" class="" />
                    <div v-if="form.errors.photo" v-text="form.errors.photo" class="text-danger"></div>
                  </div>
                </div> 
                 </div>
                </div>
                <div class="form-group">
                  <label>Content</label>
                  <Editor
      api-key="1nvwqlfylv7o7jo5qyh0s0emj2uo3ss2ytoiqzh33l2e0pls"
      v-model="form.content"
      :init="{
        plugins: 'lists link image table code help wordcount'
      }"
    />
                  <div v-if="form.errors.content" v-text="form.errors.content" class="text-danger"></div>
                </div> 
                <hr />
                <div class="form-group">
            <button type="submit" class="btn btn-primary" :disabled="form.processing"> 
                <span v-if="!form.processing"><i  class="fa fa-plus"></i> Post Story</span> 
                <span v-else-if="form.processing"><div class="spinner-border spinner-border-sm" role="status"></div> Posting...</span>
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
    props:{
      bloggers: Object,
      story: Object,
    },
    data() {
            return {
                //editor: ClassicEditor,
            };
        },
    setup(props){
        const form = useForm({
          id: props.story.id,  
          title: props.story.heading,
          photo: null,
          content: props.story.content,
          blogger: props.story.blogger_id,
          _method: 'put'
       });

       const update = (id) =>{
  form.post('/admin/stories/'+id);
};
      return {form, update};
    }
}  
</script>