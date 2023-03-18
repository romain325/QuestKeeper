<template>
  <div class="card lg:card-side bg-base-100 shadow-xl h-full" :ref="drop">
    <figure class="w-1/3"><img src="https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8aHVtYW58ZW58MHx8MHx8&w=1000&q=80" alt="Album"/></figure>
    <div class="card-body">
      <h2 class="card-title h-1/6">{{player.name}}</h2>
      <input v-if="edit" placeholder="name" type="text" class="input input-bordered w-full max-w-xs" v-model="player.name"/>
      <p class="h-1/6">{{player.desc}}</p>
      <textarea v-if="edit" placeholder="description" type="text" class="textarea textarea-bordered w-full" v-model="player.desc"/>
      <div class="flex flex-col w-full h-4/6">
        <!-- Stats -->
        <div :class="'grid place-items-center overflow-y-scroll ' + (edit ? 'h-4/6' : 'h-1/2')">
          <div class="overflow-x-auto w-full">
            <table class="table w-full">
              <!-- head -->
              <thead>
              <tr>
                <th>Stat</th>
                <th></th>
              </tr>
              </thead>
              <tbody>
              <!-- row 1 -->
              <tr v-for="stat of player.playerStats">
                <td>
                  <div class="flex items-center space-x-3">
                    <div>
                      <div class="font-bold">{{stat.name}}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge badge-ghost badge-sm">{{stat.value}}</span>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div v-if="edit">
          <input placeholder="stat name" v-model="stat.name" class="input input-bordered w-full max-w-xs"/>
          <input placeholder="stat value" v-model="stat.value" class="input input-bordered w-full max-w-xs"/>
          <button class="btn btn-circle btn-primary" style="font-size: 35px" :disabled="!stat.name || !stat.value" @click="player.playerStats.push({name: stat.name, value: stat.value, id:''})">+</button>
        </div>
        <div v-if="!edit" class="divider"></div>
        <!-- Items -->
        <div v-if="!edit" class="grid place-items-center overflow-y-scroll h-1/2">
          <div class="overflow-x-auto w-full">
            <table class="table w-full">
              <!-- head -->
              <thead>
              <tr>
                <th>Items</th>
                <th></th>
              </tr>
              </thead>
              <tbody>
              <!-- row 1 -->
              <tr v-for="item of player.playerItems">
                <td>
                  <div class="flex items-center space-x-3">
                    <div class="avatar">
                      <div class="mask mask-squircle w-12 h-12">
                        <img src="https://media.discordapp.net/attachments/1067816772000022609/1068482857565171742/Illustration_sans_titre.png?width=504&height=504" alt="Avatar Tailwind CSS Component" />
                      </div>
                    </div>
                    <div>
                      <div class="font-bold">{{item.name}}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <p>{{item.description}}</p>
                </td>
                <td v-if="editableInventory">
                  <button class="btn btn-circle btn-outline" @click="removeItem(item.id, player.id)"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <button class="btn" v-if="(!current || edit) && onActionButton" @click="onActionButton(player.id)">{{actionName}}</button>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import type {PropType} from "vue";
import type Player from "@/models/Player";
import {useDrop} from "vue3-dnd";
import type Item from "@/models/Item";
import ENVIRONMENT from "@/assets/Environement";
import * as $ from "jquery";

export default defineComponent({
  name: "PlayerComponent",
  props: {
    player: Object as PropType<Player>,
    current: {
      type: Boolean,
      default: false,
    },
    edit: {
      type: Boolean,
      default: false
    },
    actionName: {
      type: String,
      default: "Select"
    },
    editableInventory: {
      type: Boolean,
      default: false,
    },
    onActionButton: Function,
    onDrop: {
      type: Function as PropType<(item: Item) => void>,
      default: () => { console.log("drag&drop not enabled") }
    }
  },
  data() {
    return {
      stat: {
        name: "",
        value: ""
      }
    }
  },
  setup(props) {
    const [collect, drop] = useDrop({
      accept: [ 'ITEM' ],
      drop: props.onDrop,
    })
    return {
      drop
    }
  },
  methods: {
    addNewStat() {
      this.player?.playerStats.push({
        id: "",
        name: this.stat.name,
        value: this.stat.value
      });
      this.stat.name = "";
      this.stat.value = "";
    },

    removeItem(itemId: string, playerId: string) {
      $.ajax({
        method: "DELETE",
        data: JSON.stringify({itemId, playerId}),
        url: ENVIRONMENT.backendUrl + "/player/item",
        headers: {
          "Authorization": "Bearer " + this.$store.state.token
        },
      });
    }
  }
})
</script>

<style scoped>
img {
  max-width: 100%;
  height: auto;
  padding: 5px;
  border-radius: 10px;
}
</style>