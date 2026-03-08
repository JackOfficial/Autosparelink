<template>
    <Head title="HFRO - Application" />
   <Layout>
    <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Application</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><Link href="/admin">Home</Link></li>
                <li class="breadcrumb-item active">Application</li>
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
                <h3 class="card-title">Application Details</h3>
  
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <h2>{{ application.title }} Position</h2>
                <hr>
                <div><label>Applicant Name:</label> {{ application.first_name }} {{ application.last_name }}</div>
                <div><label>Applicant Email:</label> {{ application.email }}</div> 
                <div><label>Applicant Phone:</label> {{ application.phone }}</div>  
                <div><label>Applicant Address:</label> {{ application.address }}</div>  

                <div><label>Country Of Nationality:</label> {{ application.name }}</div> 
                <div><label>Highest Level Of Education:</label> {{ application.level_of_education }}</div> 
                <div><label>Field Of Study:</label> {{ application.field_of_study }}</div> 

                <div><label>Applicant Notice Period:</label> {{ application.notice_period }}</div>  
                <div><label>Applicant Desired Salary:</label> {{ application.desired_salary }} Rwf</div>
                <div><label>Resume: </label> 
                    <a :href="`/storage/${application.resume}`" target="__blank">
                    <i class="far fa-file-pdf" style="font-size:36px;"></i>
                  </a>
                </div>
                <div><label>Cover letter: </label> 
                  <a :href="`/storage/${application.cover_letter}`" target="__blank">
                    <i class="far fa-file-pdf" style="font-size:36px;"></i>
                  </a>
                </div>
                <div><label>Application Status:</label>
                  <span v-if="application.status == 0" class="badge badge-warning">Pending</span>
                  <span v-else class="badge badge-success">Active</span>
                </div>
                <div><label>Applied on:</label> {{ application.created_at }}</div>
                <hr />
                <div class="d-flex">
                  <button class="btn btn-primary rounded-pill mr-2"><i class="fa fa-check"></i> Shortlist {{ application.last_name }}</button>
                  <button class="btn btn-outline-danger rounded-pill"><i class="fa fa-times"></i> Deny</button>
                </div>
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
    components: {
        Layout, Preloader, Head, Link
    },
    props:{
        application: Object,
    },
}
</script>