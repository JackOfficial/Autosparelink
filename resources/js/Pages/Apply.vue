<template>
    <Head title="Happy Family - Job details" />
    <Layout>
        <section class="single-page-header" style="background-image: url('/Front/images/HFRO - Career.png');">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2>Job Details</h2>
				<ol class="breadcrumb header-bradcrumb">
				  <li><a href="/">Home</a></li>
          <li class="mx-1">/</li>
				  <li class="active">Job details</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="container mt-3">

  <div class="title text-center">
				<h2>Job Application for {{ career.title }}</h2>
				<div class="border"></div>
			</div>

 <div class="row">
<div class="col-md-2"></div>
<div class="col-md-8">
 <form @submit.prevent="save">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="firstname">First Name<span class="text-danger">*</span></label>
                  <input type="text" v-model="form.firstname" id="firstname" class="form-control" required />
                  <div v-if="form.errors.firstname" v-text="form.errors.firstname" class="text-danger"></div>
                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="lastname">Last Name<span class="text-danger">*</span></label>
                  <input type="text" v-model="form.lastname" id="lastname" class="form-control" required />
                  <div v-if="form.errors.lastname" v-text="form.errors.lastname" class="text-danger"></div>
                </div> 
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="email">Email<span class="text-danger">*</span></label>
                  <input type="email" v-model="form.email" id="email" class="form-control" required />
                  <div v-if="form.errors.email" v-text="form.errors.email" class="text-danger"></div>
                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="phone">Mobile Phone<span class="text-danger">*</span></label>
                  <input type="tel" placeholder="Enter mobile phone number" v-model="form.phone" id="phone" class="form-control" required />
                  <div v-if="form.errors.phone" v-text="form.errors.phone" class="text-danger"></div>
                </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="nationality">Country Of Nationality<span class="text-danger">*</span></label>
                  <select v-model="form.nationality" id="nationality" class="form-control" required>
                    <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name }}</option>
                  </select>
                  <div v-if="form.errors.nationality" v-text="form.errors.nationality" class="text-danger"></div>
                </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                  <label for="level_of_education">Level Of Education<span class="text-danger">*</span></label>
                  <select v-model="form.level_of_education" id="level_of_education" class="form-control" required>
                    <option value="High School">High School</option>
                    <option value="Bachelors">Bachelor's</option>
                    <option value="Masters">Masters</option>
                    <option value="PhD">PhD</option>
                  </select>
                  <div v-if="form.errors.study" v-text="form.errors.study" class="text-danger"></div>
                </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                  <label for="field_of_study">Field of study<span class="text-danger">*</span></label>
                  <input type="text" v-model="form.field_of_study" id="field_of_study" class="form-control" required />
                  <div v-if="form.errors.field_of_study" v-text="form.errors.field_of_study" class="text-danger"></div>
                </div>
                            </div>
                          </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                  <label for="address">Address<span class="text-danger">*</span></label>
                  <input type="address" v-model="form.address" id="address" class="form-control" required />
                  <div v-if="form.errors.address" v-text="form.errors.address" class="text-danger"></div>
                </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                  <label for="notice_period">Notice Period<span class="text-danger">*</span></label>
                  <input type="date" v-model="form.notice_period" :min="setMinimumDate" id="notice_period" class="form-control" required />
                  <div v-if="form.errors.notice_period" v-text="form.errors.notice_period" class="text-danger"></div>
                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                  <label for="salary">Desired Salary (Rwf)<span class="text-danger">*</span></label>
                  <input type="number" v-model="form.salary" id="salary" class="form-control" required />
                  <div v-if="form.errors.salary" v-text="form.errors.salary" class="text-danger"></div>
                </div>    
                                </div>
                            </div>
                            
                        </div>
                    </div>

                <div class="row">
                    <div class="col-md-6">
                    <div class="form-group">
                  <label for="resume">Upload your resume<span class="text-danger">*</span></label>
                  <div>
                    <input type="file" @input="form.resume = $event.target.files[0]" accept="application/pdf" id="resume" class="" required />
                    <div v-if="form.errors.resume" v-text="form.errors.resume" class="text-danger"></div>
                  </div>
                </div> 
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                  <label for="coverletter">Upload your cover letter</label>
                  <div>
                    <input type="file" @input="form.coverletter = $event.target.files[0]" accept="application/pdf" id="coverletter" class="" />
                    <div v-if="form.errors.coverletter" v-text="form.errors.coverletter" class="text-danger"></div>
                  </div>
                </div>  
                    </div>
                </div>

                <div class="form-group d-none">
                  <label for="career_id">Career ID</label>
                  <div>
                    <input type="number" readonly v-model="form.career_id" class="form-control" />
                    <div v-if="form.errors.career_id" v-text="form.errors.career_id" class="text-danger"></div>
                  </div>
                </div> 
                
                <hr />
                <div class="form-group d-flex justify-content-between ">
            <a href="/career" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary" :disabled="form.processing"> 
                <span v-if="!form.processing">Submit <i  class="fa fa-sent"></i></span> 
                <span v-else-if="form.processing"><div class="spinner-border spinner-border-sm" role="status"></div> Submitting...</span>
            </button>
          </div>
                </form>
</div>
<div class="col-md-2"></div>
 </div>
</section>

    </Layout>
  </template>

<script>
import { Head, Link, useForm } from '@inertiajs/vue3';
import Layout from "../Shared/Layout.vue";

export default{
  components: {Layout, Head, Link},
  props: {
    career: Object,
    countries: Object,
},
data(){
let today = new Date();
let dd = (today.getDate()<10) ? '0' + today.getDate() : today.getDate();
let mm = (today.getMonth() + 1 < 10) ? '0' + (today.getMonth() + 1) : (today.getMonth() + 1); //January is 0!
let yyyy = today.getFullYear(); 
let dateOfToday = yyyy + '-' + mm + '-' + dd;
    return {
      setMinimumDate: dateOfToday
    }
  },
  setup(props){
  const form = useForm({
    career_id: props.career.id,
    firstname: null,
    lastname: null,
    email: null,
    phone: null,
    address: null,
    nationality: null,
    level_of_education: null,
    field_of_study: null,
    notice_period: null,
    salary: null,
    resume: null,
    coverletter: null,
  });
  const save = () =>{
    form.post('/apply');
  };

  return {form, save};

  }
}

</script>