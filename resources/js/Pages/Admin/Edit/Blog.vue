<template>
    <Head title="HFRO - Edit Blog" />
   <Layout>
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Edit Blog</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Edit Blog</li>
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
                <h3 class="card-title">Edit Blog</h3>
  
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
                    <div class="col-md-6">
                        <div class="form-group">
                  <label for="category">Blog Category</label>
                  <select v-model="form.category" id="category" class="form-control">
                   <option v-for="category in categories" :value="category.id" :key="category.id">{{ category.blog_category }}</option>
                  </select>
                  <div v-if="form.errors.category" v-text="form.errors.category" class="text-danger"></div>
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
                    <input type="file" @input="form.photo = $event.target.files[0]" id="photo" class="" />
                    <div v-if="form.errors.photo" v-text="form.errors.photo" class="text-danger"></div>
                  </div>
                </div> 
                 </div>
                </div>
                <div class="form-group">
                  <label for="summernote">Content</label>
                  <ckeditor :editor="editor" v-model="form.content"></ckeditor>
                  <div v-if="form.errors.content" v-text="form.errors.content" class="text-danger"></div>
                </div> 
                <hr />
                <div class="form-group">
            <button type="submit" class="btn btn-primary" :disabled="form.processing"> 
                <span v-if="!form.processing"><i  class="fa fa-plus"></i> Update</span> 
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
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import CKEditor from '@ckeditor/ckeditor5-vue';

export default{
    components: {
        Layout, Preloader, Head, Link, ClassicEditor, ckeditor: CKEditor.component
    },
    props:{
        categories: Object, bloggers: Object, blog: Object
    },
    data() {
            return {
                editor: ClassicEditor,
                editorConfig: {
                    // The configuration of the editor.
                }
            };
        },
    setup(props){
        const form = useForm({
    id: props.blog.id, 
    title: props.blog.title, 
    category: props.blog.blog_category_id,
    photo: null,
    content: props.blog.content,
    blogger: props.blog.blogger_id,
    _method: 'put'
       });

      const update = (id) =>{
        form.post('/admin/blogs/'+id);
      };

      return {form, update};
    }
}
</script>