<template>
  <div class="form-control w-full max-w-xs">
    <label class="label">
      <span class="label-text">Username</span>
    </label>
    <input type="text" placeholder="Type here" v-model="username" class="input input-bordered w-full max-w-xs" />
  </div>
  <div class="form-control w-full max-w-xs">
    <label class="label">
      <span class="label-text">Password</span>
    </label>
    <input type="password" placeholder="Type here" v-model="password" class="input input-bordered w-full max-w-xs" />
  </div>
  <div class="alert alert-error shadow-lg" v-if="error">
    <div>
      <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
      <span>{{error}}</span>
    </div>
  </div>
  <div class="form-control w-full max-w-xs m-6 items-center">
    <button class="btn btn-wide" @click="login()">Login</button>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import * as $ from "jquery";

export default defineComponent({
  name: "Login",
  data() {
    return {
      isLoading: false,
      username: "",
      password: "",
      error: null as string|null
    }
  },
  methods: {
    login() {
      if(this.username == "" || this.password == "") {
        console.log("provide info")
        return;
      }
      this.isLoading = true;
      const res = $.ajax({
        method: "POST",
        url: "http://localhost/login",
        data: {
          login: this.username,
          password: this.password
        },
        dataType: "application/x-www-form-urlencoded",
        async: false
      });
      if(res.status == 200) {
        const token = JSON.parse(res.responseText)["token"]
        this.$store.commit("setToken", token);
        this.$router.push("/");
      } else {
        this.error = res.responseText;
      }
      this.isLoading = false;
    }
  }
})
</script>

<style scoped>

</style>