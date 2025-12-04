<template>
    <Head title="HFRO - Update Project" />
   <Layout>
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Update Project</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Update Project</li>
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
                <h3 class="card-title">Update Project</h3>
  
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <form @submit.prevent="update(form.id)">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                  <label for="cause">Cause</label>
                  <select v-model="form.cause" id="cause" class="form-control">
                   <option :selected="form.cause === cause.id" v-for="cause in causes" :value="cause.id" :key="cause.id">{{ cause.cause }}</option>
                  </select>
                  <div v-if="form.errors.cause" v-text="form.errors.cause" class="text-danger"></div>
                </div>
                  </div>
                  <div class="col-md-8">
                    <div class="form-group">
                  <label for="project">Project</label>
                  <input type="text" v-model="form.project" id="project" class="form-control" required />
                  <div v-if="form.errors.project" v-text="form.errors.project" class="text-danger"></div>
                </div>
                   </div>
                </div>

                <div class="form-group">
                  <label for="description">Project Description</label>
                  <Editor
      api-key="1nvwqlfylv7o7jo5qyh0s0emj2uo3ss2ytoiqzh33l2e0pls"
      v-model="form.description"
      :init="{
        plugins: 'lists link image table code help wordcount'
      }"
    />
    <div v-if="form.errors.description" v-text="form.errors.description" class="text-danger"></div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                  <label for="period">Period (Months)</label>
                  <input type="number" v-model="form.period" min="0" id="period" class="form-control" placeholder="Ex: number in months" />
                  <div v-if="form.errors.period" v-text="form.errors.period" class="text-danger"></div>
                </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                  <label for="project_progress">Progress %</label>
                  <input type="number" v-model="form.project_progress" id="project_progress" min="0" max="100" class="form-control" />
                  <div v-if="form.errors.project_progress" v-text="form.errors.project_progress" class="text-danger"></div>
                </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                  <label for="budget">Budget (Rwf)</label>
                  <input type="number" v-model="form.budget" min="0" id="budget" class="form-control" />
                  <div v-if="form.errors.budget" v-text="form.errors.budget" class="text-danger"></div>
                </div>
                    </div>
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
                <span v-if="!form.processing"><i  class="fa fa-plus"></i> Update Project</span> 
                <span v-else-if="form.processing"><div class="spinner-border spinner-border-sm" role="status"></div> Updating...</span>
            </button>
          </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-md-4">
            <img :src="`/storage/${currentPhoto}`" :alt="form.project" class="img img-responsive w-100 rounded shadow" />
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
  props: {
    causes : Object, project: Object
  },
components: {
  Layout, Preloader, Head, Link, Editor
},
  data(props) {
            return {
              currentPhoto: props.project.photo, 
            };
        },
setup(props){
  let form = useForm({
    id: props.project.id,
    cause: props.project.cause_id,
    project: props.project.project,
    photo: null,
    description: props.project.description,
    budget: props.project.budget,
    period: props.project.period,
    project_progress: props.project.progress,
            _method: 'put'
    });

const update = (id) =>{
  form.post('/admin/projects/'+id);
};

return {form, update};
}
}
</script>