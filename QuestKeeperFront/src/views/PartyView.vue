<template>
  <div class="flex flex-wrap w-full h-full">
    <div class="w-1/2 h-full">
    <!--admin: drag & drop + item, player: profile -->
      <div v-if="!isMaster" class="w-full h-full">
        <PlayerComponent v-if="currentPlayer" :player="currentPlayer" />
        <h1 v-else>Loading current player ...</h1>
      </div>
      <div v-else class="w-full h-full">
        <div class="w-full h-2/3">
          <div class="h-5/6 overflow-y-scroll">
            <ItemDisplay v-for="item of partyItems" :item="item" :drag="true"/>
          </div>
          <!-- The button to open modal -->
          <label for="my-modal" class="btn btn-primary">add party items</label>

          <!-- Put this part before </body> tag -->
          <input type="checkbox" id="my-modal" class="modal-toggle" />
          <div class="modal h-full">
            <div class="modal-box h-full">
              <h3 class="font-bold text-lg">All Items</h3>
              <div class="w-full h-4/5 overflow-y-scroll">
                <ItemDisplay v-for="item of allItems" :item="item"
                             @click="addItemToSelection(item)"
                             :class="this.selectedItems.includes(item.id) ? 'bg-stone-200' : ''"/>
              </div>
              <div class="modal-action">
                <label for="my-modal" class="btn" @click="resetSelection">cancel</label>
                <label for="my-modal" class="btn btn-primary" @click="addToPartyItems">add</label>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full h-1/3">
          <input type="text" placeholder="Name" class="input input-ghost w-full max-w-xs" name="item_name" v-model="newItemName" />
          <input type="text" placeholder="Desc" class="input input-ghost w-full max-w-xs" name="item_desc" v-model="newItemDesc"/>
          <button class="btn" @click="createItem()">Create new item</button>
          <hr>
          <button class="btn" @click="dissolve()">Dissolve</button>
          <hr>
          <button v-for="player of players" class="btn" @click="kickPlayer(player.id)">Kick {{player.name}}</button>
        </div>
      </div>

    </div>
    <div class="w-1/2 h-full">
      <div class="w-full h-2/3">
        <!-- admin: interactive team, player: not interactive team -->
        <div class="flex justify-center w-full py-2 gap-2" >
          <a :href="'#slide' + index" class="btn btn-xs" v-for="(player, index) of otherPlayers">{{ index+1 }}</a>
        </div>
        <div class="carousel w-full h-4/5">
          <div :id="'slide' + index" class="carousel-item relative w-full overflow-y-scroll" v-for="(player, index) of otherPlayers">
            <PlayerComponent :player="player" @drop="onPlayerDrop(player.id, $event)" :editable-inventory="isMaster" />
          </div>
        </div>
      </div>
      <div class="w-full h-1/3">
        <DiceRoller />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import {useStore} from "vuex";
import {useRouter} from "vue-router";
import DiceRoller from "@/components/DiceRoller.vue";
import PlayerComponent from "@/components/PlayerComponent.vue";
import type Player from "@/models/Player";
import * as $ from 'jquery';
import ItemDisplay from "@/components/ItemDisplay.vue";
import type Item from "@/models/Item";
import ENVIRONMENT from "@/assets/Environement";

export default defineComponent({
  name: "PartyView",
  components: {ItemDisplay, PlayerComponent, DiceRoller},
  beforeMount() {
    const store = useStore();
    if(!store.state.party){
      const router = useRouter();
      router.push("/");
    }

    const res = $.ajax({
      method: "POST",
      url: ENVIRONMENT.backendUrl + "/party/master",
      data: JSON.stringify({id: this.$store.state.party}),
      headers: {
        "Authorization": "Bearer " + this.$store.state.token
      },
      async: false,
    });
    if(res.status != 200) {
      // error
      console.error(res);
    } else {
      if(JSON.parse(res.responseText).master == this.$store.state.user) {
        this.isMaster = true;
      }
    }

  },
  methods: {
    dissolve() {
      $.ajax({
        method:"DELETE",
        url: ENVIRONMENT.backendUrl + "/party",
        data: JSON.stringify({
          id: this.$store.state.party,
        }),
        headers: {
          "Authorization": "Bearer " + this.$store.state.token
        },
      });
    },
    kickPlayer(id: string) {
      $.ajax({
        method: "DELETE",
        url: ENVIRONMENT.backendUrl + "/party/player",
        data: JSON.stringify({
          party: this.$store.state.party,
          player: id
        }),
        headers: {
          "Authorization": "Bearer " + this.$store.state.token
        }
      })
    },
    addItemToSelection(item: Item) {
      if(this.selectedItems.includes(item.id)) {
        this.selectedItems.splice(this.selectedItems.indexOf(item.id), 1);
      } else {
        this.selectedItems.push(item.id);
      }
    },
    resetSelection() {
      this.selectedItems = [];
    },
    createItem() {
      if(this.newItemName != "") {
        $.ajax({
          method: "POST",
          url: ENVIRONMENT.backendUrl + "/item",
          data: JSON.stringify({
            name: this.newItemName,
            desc: this.newItemDesc
          }),
          headers: {
            "Authorization": "Bearer " + this.$store.state.token
          },
          complete: (res, status) => {
            // refresh itemss by changing a value that is referenced in the computed "allItems"
            this.forceRefresher++;
            alert("item created");
          }
        })
      } else {
        alert("No name nor description provided");
      }
    },
    addToPartyItems() {
      $.ajax({
        method: "PUT",
        url: ENVIRONMENT.backendUrl + "/party/items",
        data: JSON.stringify({
          id: this.$store.state.party,
          items: this.selectedItems
        }),
        headers: {
          "Authorization": "Bearer " + this.$store.state.token
        },
        complete: (res,status) => {
          if(res.status != 200) {
            console.error(res);
          } else {
            this.partyItems = JSON.parse(res.responseText);
          }
        }
      })
      this.resetSelection();
    },
    onPlayerDrop(playerId: string, item: Item) {
      $.ajax({
        method: "PUT",
        url: ENVIRONMENT.backendUrl + "/player/item",
        data: JSON.stringify({
          playerId: playerId,
          itemId: item.id
        }),
        headers: {
          "Authorization": "Bearer " + this.$store.state.token
        },
      });
    },
    setRefreshInterval() {
      this.interKey = setInterval(() => {
        // refresh players by requesting /party/players
        $.ajax({
          method: "POST",
          url: ENVIRONMENT.backendUrl + "/party/players",
          data: JSON.stringify({id: this.$store.state.party}),
          headers: {
            "Authorization": "Bearer " + this.$store.state.token
          },
          complete: (res, status) => {
            if(res.status != 200) {
              // error
              console.error(res);
            } else {
              this.players = JSON.parse(res.responseText);
            }
          }
        });
      }, 1000);
    },

  },

  mounted() {
    this.setRefreshInterval();
    if(!this.isMaster){
      $.ajax({
        method: "GET",
        url: ENVIRONMENT.backendUrl + "/player",
        headers: {
          "Authorization": "Bearer " + this.$store.state.token
        },
        complete: (res, status) => {
          if(res.status != 200) {
            console.error(res);
          } else {
            this.currentPlayerId = JSON.parse(res.responseText).id;
          }
        }
      })
    } else {
      $.ajax({
        method: "POST",
        url: ENVIRONMENT.backendUrl + "/party/items",
        data: JSON.stringify({id: this.$store.state.party }),
        headers: {
          "Authorization": "Bearer " + this.$store.state.token
        },
        complete: (res, status) => {
          if(res.status != 200) {
            console.error(res);
          } else {
            this.partyItems = JSON.parse(res.responseText);
          }
        }
      })
    }
  },
  data() {
    return {
      players: [] as Player[],
      isMaster: false,
      currentPlayerId: "",
      selectedItems: [] as string[],
      partyItems: [] as Item[],
      interKey: -1,
      newItemName: "",
      newItemDesc: "",
      forceRefresher: 0,
    }
  },
  computed: {
    currentPlayer() {
      if(this.currentPlayerId != null) {
        const user = this.players.filter(x => x.id == this.currentPlayerId);
        return (user.length > 0) ? user[0] : null;
      } else {
        return null;
      }
    },
    otherPlayers() {
      return this.players.filter(x => x.id != this.currentPlayerId);
    },
    allItems() : Item[] {
      this.forceRefresher;
      const res = $.ajax({
        method: "GET",
        url: ENVIRONMENT.backendUrl + "/items",
        async: false,
        headers: {
          "Authorization": "Bearer " + this.$store.state.token
        },
      });
      return JSON.parse(res.responseText);
    }
  },

  unmounted() {
    if(this.interKey != -1) {
      clearInterval(this.interKey);
      this.interKey = -1;
    }
  }

})
</script>

<style scoped>
.w-1\/2 {
  width: 50%;
  padding: 10px;
}
</style>