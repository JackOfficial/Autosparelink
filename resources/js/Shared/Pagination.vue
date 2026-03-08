<template>
    <nav aria-label="Page navigation example">
  <ul class="pagination justify-content-center">
   
    <li v-for="(link, key) in links" :key="key" class="page-item" :class="{'font-weight-bold': link.active}">
      <Link class="page-link" :href="link.url"> {{ link.label }}</Link>
    </li>
   
  </ul>
</nav>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { defineComponent } from 'vue';

defineProps({links: Array});
defineComponent({Link});
</script>
