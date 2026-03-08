<template>
    <Head title="HFRO - Add Cause" />
   <Layout>
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Add Career</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Add Career</li>
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
                <h3 class="card-title">Add Career</h3>
  
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
                  <label for="title">Title</label>
                  <input type="text" v-model="form.title" id="title" class="form-control" required />
                  <div v-if="form.errors.title" v-text="form.errors.title" class="text-danger"></div>
                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="jobtype">Job type</label>
                  <select v-model="form.jobtype" id="jobtype" class="form-control">
                   <option v-for="jobtype in jobtypes" :value="jobtype.id" :key="jobtype.id">{{ jobtype.jobtype }}</option>
                  </select>
                 <div v-if="form.errors.jobtype" v-text="form.errors.jobtype" class="text-danger"></div>
                </div> 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="qualification">Qualification</label>
                  <input type="text" v-model="form.qualification" id="qualification" class="form-control" required />
                  <div v-if="form.errors.qualification" v-text="form.errors.qualification" class="text-danger"></div>
                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="deadline">Deadline</label>
                  <input type="date" v-model="form.deadline" id="deadline" class="form-control" required />
                  <div v-if="form.errors.deadline" v-text="form.errors.deadline" class="text-danger"></div>
                </div>
                        </div>
                    </div>
                <div class="form-group">
                  <label for="description">Job Description</label>
                  <ckeditor :editor="editor" v-model="form.description"></ckeditor>
                  <div v-if="form.errors.description" v-text="form.errors.description" class="text-danger"></div>
                </div>
                
                <hr />
                <div class="form-group d-flex justify-content-between ">
            <a href="#" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary" :disabled="form.processing"> 
                <span v-if="!form.processing"><i  class="fa fa-plus"></i> Update</span> 
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
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import CKEditor from '@ckeditor/ckeditor5-vue';

export default{
    props: { jobtypes: Object, career: Object },
    components: { Layout, Preloader, Head, Link, ClassicEditor, ckeditor: CKEditor.component },
    data() {
            return {
                editor: ClassicEditor,
                editorConfig: {
                    // The configuration of the editor.
                }
            };
        },
    setup(props){
        let form = useForm({
    id: props.career.id,
    title: props.career.title,
    jobtype: props.career.jobtype_id,
    description: props.career.description,
    qualification: props.career.qualification,
    deadline: props.career.deadline
});

const update = (id) =>{
          form.put('/admin/careers/'+id);
     };
return {form, update};
    }
}

</script>