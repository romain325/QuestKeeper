<template>
  <div class="flex flex-col h-5/6 items-center">
    <h1 class="text-5xl font-bold mt-12 h-2/4">QuestKeeper</h1>
    <div class="h-1/4 w-5/6"><button class="btn btn-primary w-full">Login</button></div>
    <div class="h-1/4 w-5/6"><button class="btn w-full">Signup</button></div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
  name: "ConnectionView"
})
</script>

<style scoped>

</style>