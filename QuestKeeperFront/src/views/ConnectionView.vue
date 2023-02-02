<template>

  <div class="flex flex-col h-5/6 items-center">
    <h1 class="text-5xl font-bold mt-12 h-2/4">QuestKeeper</h1>
    <button class="btn self-start" v-if="isLogin || isSignup" @click="isSignup = isLogin = false">Back</button>
    <div  v-if="!isLogin && !isSignup" class="">
      <div class="btn-wide m-5"><button class="btn btn-primary w-full" @click="isLogin = !isLogin" >Login</button></div>
      <div class="btn-wide m-5"><button class="btn w-full" @click="isSignup = !isSignup">Signup</button></div>
    </div>
    <Login v-if="isLogin"/>
    <Signup v-if="isSignup" />
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import Login from "@/components/Login.vue";
import Signup from "@/components/Signup.vue";

export default defineComponent({
  name: "ConnectionView",
  components: {Signup, Login},
  mounted() {
    if(this.$store.state.token) {
      this.$router.push("/");
    }
  },
  data() {
    return {
      isLogin: false,
      isSignup: false,
    }
  }
})
</script>

<style scoped>

</style>