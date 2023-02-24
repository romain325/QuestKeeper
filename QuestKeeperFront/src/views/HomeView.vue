<script lang="ts">

import {defineComponent} from "vue";
import TheWelcome from '../components/TheWelcome.vue'
import * as $ from 'jquery';
import ENVIRONMENT from "@/assets/Environement";

export default defineComponent({
  components: {TheWelcome},
  data() {
    return {
      code: "",
      name: "",
      error: ""
    }
  },
  methods: {
    createParty() {
      if(!this.name) return;
      const res = $.ajax({
        method: "POST",
        url: ENVIRONMENT.backendUrl + "/party",
        data: JSON.stringify({ "name" : this.name}),
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + this.$store.state.token
        },
        contentType: "application/json",
        crossDomain: true,
        async: false,
      });
      if(res.status==200) {
        this.code = JSON.parse(res.responseText).code;
      } else {
        console.log(res);
      }
    },
    joinParty() {
      if(!this.code) return;
      $.ajax({
        method: "POST",
        url: ENVIRONMENT.backendUrl + "/party/join",
        data: JSON.stringify({"code": this.code}),
        headers: {
          "Authorization": "Bearer " + this.$store.state.token
        },
        contentType: "application/json",
        complete: (res, status) => {
          if(res.status == 200 && JSON.parse(res.responseText)?.result) {
            this.$store.state.party = JSON.parse(res.responseText)?.result;
            this.$router.push("/party");
          } else {
            this.error = JSON.parse(res.responseText)?.message;
          }
        }
      })
    }
  }
});
</script>

<template>
  <main class="">
    <h1 class="text-5xl flex justify-center font-bold mt-12 mb-6 h-2/4">QuestKeeper</h1>
    <div class="flex items-end grow justify-center">
      <div class="form-control w-full max-w-xs m-6">
        <label class="label">
          <span class="label-text">Party Code</span>
        </label>
        <input type="text" v-model="code" placeholder="Type code" class="input input-bordered w-full max-w-xs" />
      </div>
      <button class="btn btn-primary m-6" @click="joinParty()">Join Party</button>
    </div>

    <div class="flex justify-center">
      <label for="my-modal-4" class="btn btn-outline btn-primary">Create Party</label>
    </div>



    <input type="checkbox" id="my-modal-4" class="modal-toggle" />
    <div class="modal">
      <label class="modal-box relative" for="">
        <label for="my-modal-4" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
        <div v-if="!code">
          <label class="label">
            <span class="label-text">Party name</span>
          </label>
          <input type="text" placeholder="Party name" class="input input-bordered w-full max-w-xs" v-model="name"/>
          <button class="btn btn-primary m-6" @click="createParty()">Generate</button>
        </div>
        <div v-else>
          <h3 class="text-lg font-bold">"Party created"</h3>
          <p class="py-4">You can now join with this code: {{code}}</p>
        </div>
      </label>
    </div>

  </main>
</template>
