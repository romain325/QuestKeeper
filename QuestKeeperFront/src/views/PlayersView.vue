<template>
  <div class="drawer drawer-mobile h-full">
    <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col items-center justify-center">
      <!-- Page content here -->
      <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden">Open Players</label>
      <h1 v-if="!selectedPlayer">No player selected</h1>
      <PlayerComponent v-else :player="selectedPlayer" :current="selectedPlayer.id === currentPlayer" :on-selection-changed="changeCurrentPlayer" ></PlayerComponent>
    </div>
    <div class="drawer-side">
      <label for="my-drawer-2" class="drawer-overlay"></label>
      <ul class="menu p-4 w-80 bg-base-100 text-base-content" v-for="player of players">
        <li :key="player.id" @click="this.selectedPlayer = player" :class="player.id === currentPlayer ? 'bg-base-300' : ''" ><a>{{player.name}}</a></li>
      </ul>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import * as $ from 'jquery';
import type Player from "@/models/Player";
import PlayerComponent from "@/components/PlayerComponent.vue";

export default defineComponent({
  name: "PlayersView",
  components: {PlayerComponent},
  data() {
    return {
      currentPlayer: null as string|null,
      selectedPlayer: null as Player|null,
      players: [] as Player[],
    }
  },
  methods: {
    changeCurrentPlayer(id: string) {
      const res = $.ajax({
        method: "PUT",
        url: "http://localhost/player",
        async: false,
        data: JSON.stringify({id}),
        headers: {
          "Authorization": "Bearer " + this.$store.state.token,
        }
      });
      if(res.status == 200) {
        this.currentPlayer = id;
      }
    }
  },
  mounted() {
    const res = $.ajax({
      method: "GET",
      url: "http://localhost/players",
      async: false,
      headers: {
        "Authorization": "Bearer " + this.$store.state.token,
      }
    });
    if(res.status == 200) {
      this.players = JSON.parse(res.responseText);
    }

    const resSelected = $.ajax({
      method: "GET",
      url: "http://localhost/player",
      async: false,
      headers: {
        "Authorization": "Bearer " + this.$store.state.token,
      }
    });
    if(resSelected.status == 200) {
      this.currentPlayer = (JSON.parse(resSelected.responseText) as Player).id;
      console.log(this.currentPlayer);
    }
  }
});
</script>

<style scoped>

</style>