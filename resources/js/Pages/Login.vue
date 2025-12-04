<template>
     <Head title="Happy Family - Login" />
  <Layout>
<div class="form-group row mb-0">
<div class="col-md-8 offset-md-4">
<a href="{{ url('/redirect') }}" class="btn btn-primary"><i class="fa fa-google"></i> Google</a>
</div>
</div>
</Layout>
</template>

<script>
// import Layout from './Layout'
import { Head, Link } from '@inertiajs/vue3';
import Layout from "../Shared/Layout.vue";

// defineProps({ name: Object })
export default{
  components: { Layout, Head, Link },
  props: {
    'pageName': String
  },
  data(){
    return { }
  }
}

</script>