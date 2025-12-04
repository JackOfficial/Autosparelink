<template>
 <footer class="main-footer">
    <strong>Copyright &copy; {{ dateOfToday }} <a href="/">HFRO</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.2.0
    </div>
  </footer>
</template>

<script>
export default {
   data(){
    return{
      dateOfToday: 2023
    }
   } 
}
</script>
