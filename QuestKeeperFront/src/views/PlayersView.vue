<template>
  <button class="btn" @click="this.$router.push('/players/create')">Create new player</button>
  <div class="drawer drawer-mobile h-full">
    <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col items-center justify-center">
      <!-- Page content here -->
      <label for="my-drawer-2" class="btn btn-primary drawer-button lg:hidden">Open Players</label>
      <h1 v-if="!selectedPlayer">No player selected</h1>
      <PlayerComponent v-else action-name="Select" :player="selectedPlayer" :current="selectedPlayer.id === currentPlayer" :on-action-button="changeCurrentPlayer" ></PlayerComponent>
    </div>
    <div class="drawer-side">
      <label for="my-drawer-2" class="drawer-overlay"></label>
      <ul class="menu p-4 w-80 bg-base-100 text-base-content" v-for="player of players">
        <li class="inline-flex flex-row flex-wrap justify-between" :key="player.id" @click="this.selectedPlayer = player" :class="player.id === currentPlayer ? 'bg-base-300' : ''" >
          <a>{{player?.name}}</a>
          <button class="btn btn-circle" @click="deletePlayer(player.id)">-</button>
        </li>
      </ul>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import * as $ from 'jquery';
import type Player from "@/models/Player";
import PlayerComponent from "@/components/PlayerComponent.vue";
import ENVIRONMENT from "@/assets/Environement";

export default defineComponent({
  name: "PlayersView",
  components: {PlayerComponent},
  data() {
    return {
      currentPlayerId: null as string|null,
      selectedPlayer: null as Player|null,
      players: [] as Player[],
    }
  },
  methods: {
    deletePlayer(id :string) {
      $.ajax({
        method: "DELETE",
        url: ENVIRONMENT.backendUrl + "/player",
        data: JSON.stringify({id}),
        headers: {
          "Authorization": "Bearer " + this.$store.state.token,
        }
      });
      this.refreshPlayers();
    },
    changeCurrentPlayer(id: string) {
      const res = $.ajax({
        method: "PUT",
        url: ENVIRONMENT.backendUrl + "/player",
        async: false,
        data: JSON.stringify({id}),
        headers: {
          "Authorization": "Bearer " + this.$store.state.token,
        }
      });
      if(res.status == 200) {
        this.currentPlayer = id;
      }
    },
    refreshPlayers() {
      $.ajax({
        method: "GET",
        url: ENVIRONMENT.backendUrl + "/players",
        async: false,
        headers: {
          "Authorization": "Bearer " + this.$store.state.token,
        },
        complete: (res, status) => {
          if(res.status == 200) {
            this.players = JSON.parse(res.responseText);
          }
        }
      });


      $.ajax({
        method: "GET",
        url: ENVIRONMENT.backendUrl + "/player",
        headers: {
          "Authorization": "Bearer " + this.$store.state.token,
        },
        complete: (res, status) => {
          if(res.status == 200) {
            this.currentPlayer = (JSON.parse(res.responseText) as Player).id;
            this.selectedPlayer = JSON.parse(res.responseText);
          }
        }
      });

      if(!this.currentPlayerId){
        this.selectedPlayer = null;
      }
    }
  },
  mounted() {
    this.refreshPlayers();
  }
});
</script>

<style scoped>
  button {
    margin: 5px;
  }

  .drawer-side {
    margin: 5px;
  }
  .drawer-content {
    margin: 5px;
  }
</style>