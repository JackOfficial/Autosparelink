<template>
	<Head title="Happy Family - Application feedback" />
	<Layout>

<section class="section container my-5 text-center">
    <i class="fa fa-check" style="font-size: 100px;"></i>
      <h2>Your application was sent successfully.</h2>
      <p>You will recieve confirmation email shortly!</p>
      <p>Thank you for your time.</p>
</section>
  
	</Layout>
  </template>
  
  <script setup>
  // import Layout from './Layout'
  import { Head, Link, router } from '@inertiajs/vue3';
  import Layout from "../Shared/Layout.vue";
import { defineComponent, onUnmounted } from 'vue';
   defineComponent({
	Layout, Head, Link,
   });
   defineProps({
      // application_sent: String
   });

// router.on('success', (event) => {
//    //toastr.success("Application has been sent successfully!");
//    swal({
// 			title: "Application sent",
// 			text: "You will recieve confirmation email shortly!",
// 			icon: "success",
//            });
// });

let removeStartEventListener = router.on('success', (event) => {
   swal({
			title: "Application sent",
			text: "You will recieve confirmation email shortly!",
			icon: "success",
           });
  })

  removeStartEventListener();
  </script>