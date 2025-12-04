<template>
   <Head title="HFRO - Edit Cause" />
   <Layout>
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Edit Cause</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Edit Cause</li>
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
              <h3 class="card-title">Add Cause</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <form @submit.prevent="update(form.id)">
                <div class="form-group">
                <label for="cause">Cause</label>
                <input type="text" v-model="form.cause" id="cause" class="form-control" required />
                <div v-if="form.errors.cause" v-text="form.errors.cause" class="text-danger"></div>
              </div>
              <div class="form-group">
                <label for="description">Cause Description</label>
                <textarea id="description" v-model="form.description" class="form-control" rows="4"></textarea>
                <div v-if="form.errors.description" v-text="form.errors.description" class="text-danger"></div>
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
              <span v-if="!form.processing"><i  class="fa fa-plus"></i> Update Cause</span> 
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
  props: { cause: Object },
  components: { Layout, Preloader, Head, Link },
  setup(props){
    const form = useForm({
  id: props.cause.id,
  cause: props.cause.cause,
  description: props.cause.description,
  photo: null,
  _method: 'put'
});

const update = (id) =>{
  form.post('/admin/causes/'+id);
};

return {form, update};
  }
}
</script>