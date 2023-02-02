<script lang="ts">

import {defineComponent} from "vue";
import TheWelcome from '../components/TheWelcome.vue'


export default defineComponent({
  components: {TheWelcome},
  mounted() {
    if(!this.$store.state.token) {
      this.$router.push("/login")
    }
  }
})
</script>

<template>
  <main>
    <TheWelcome />
  </main>
</template>
