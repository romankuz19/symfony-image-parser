<template>
  <div>
    <div class="container d-flex justify-content-center">
      <div>
        <h2 class="text-center">Image parser</h2>
        <form class="d-flex flex-column gap-3 justify-content-center" action="" method="post">
          <p class="text-center">Url is: {{ url }}</p>
          <input class="form-control" v-model="url" placeholder="Enter URL" />
          <button class="btn btn-secondary" @click="getImagesUrl" type="submit">Send</button>
        </form>
        <h2 class="text-center" v-if="!isHidden">Pending...</h2>
      </div>
    </div>
    <div class="row row-cols-4">
      <ShowImages
          v-for="image in images"
          :path="image['path']">
      </ShowImages>
    </div>
    <h4 class="text-center mt-2" v-if="totalWeight !== -1">
      На странице обнаружено {{images.length}} изображений на {{totalWeight.toFixed(0)}} Мб
    </h4>
  </div>
</template>

<script setup>
import {ref} from 'vue'
import axios from "axios";
import ShowImages from "./ShowImages.vue";

const url = ref("")
const endpoint = 'http://localhost:80/api/v1/parse-images/'

let images = ref([])
let totalWeight = ref(-1)
let isHidden = ref(true)

function isUrlValid(string) {
  try {
    new URL(string);
    return true;
  } catch (err) {
    return false;
  }
}

async function getImagesUrl(e) {
  e.preventDefault()
  isHidden.value = false;
  if (!isUrlValid(url.value)) {
    alert('Введите корректный URL')
  } else {
      try {
        let escapedUrl = url.value.replace(/\//g, "*")
        const response = await axios.get(endpoint.concat(escapedUrl));
        images.value = response.data.images
        totalWeight.value = response.data.totalWeight
      } catch (error) {
        alert(error.message)
      }
  }
  isHidden.value = true;
}

</script>

<style>
body {
  background-color: lightgray;
  max-width: 1400px;
  margin: auto;
}
</style>