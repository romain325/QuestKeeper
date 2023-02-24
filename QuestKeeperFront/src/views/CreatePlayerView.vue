<template>
  <PlayerComponent v-if="player" :player="player" :edit="true" :on-action-button="createPlayer" action-name="Create"></PlayerComponent>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import type Player from "@/models/Player";
import PlayerComponent from "@/components/PlayerComponent.vue";
import * as $ from 'jquery';
import ENVIRONMENT from "@/assets/Environement";

export default defineComponent({
  name: "CreatePlayerView",
  components: {PlayerComponent},
  data() {
    return {
      player: null as Player|null,
    }
  },
  mounted() {
    this.player = {
      id: "",
      playerItems: [],
      playerStats: [],
      desc: "",
      name: ""
    }
  },
  methods: {
    createPlayer(_: string) {
      $.ajax({
        method: "POST",
        url: ENVIRONMENT.backendUrl + "/player",
        data: JSON.stringify(this.player),
        dataType: "json",
        headers: {
          "Authorization": "Bearer " + this.$store.state.token
        },
        complete: (val, status) => {
          this.$router.push("/players");
        }
      })
    }
  }
})
</script>

<style scoped>

</style>