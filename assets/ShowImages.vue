<script >
import { defineComponent } from 'vue'

export default defineComponent ({
  name:'ShowImages',
  props: {
    path: String,
    weight: Number,
  }
})

</script>

<template>
    <img class="col mt-4 border border-dark" :src="path" alt="" width="200" height="200">
</template>

<style scoped>

</style>