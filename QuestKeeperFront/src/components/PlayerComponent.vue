<template>
  <div class="card lg:card-side bg-base-100 shadow-xl h-full">
    <figure class="w-1/3"><img src="https://images.unsplash.com/photo-1503023345310-bd7c1de61c7d?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8aHVtYW58ZW58MHx8MHx8&w=1000&q=80" alt="Album"/></figure>
    <div class="card-body">
      <h2 class="card-title h-1/6">{{player.name}}</h2>
      <p class="h-1/6">{{player.desc}}</p>
      <div class="flex flex-col w-full h-4/6">
        <!-- Stats -->
        <div class="grid place-items-center overflow-y-scroll h-1/2">
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
                      <div class="text-sm opacity-50">{{ stat.id }}</div>
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
        <div class="divider"></div>
        <!-- Items -->
        <div class="grid place-items-center overflow-y-scroll h-1/2">
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
                        <img src="/tailwind-css-component-profile-2@56w.png" alt="Avatar Tailwind CSS Component" />
                      </div>
                    </div>
                    <div>
                      <div class="font-bold">{{item.name}}</div>
                      <div class="text-sm opacity-50">{{ item.id }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <p>{{item.description}}</p>
                </td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <button class="btn" v-if="!current" @click="onSelectionChanged(player.id)">Select</button>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import type {PropType} from "vue";
import type Player from "@/models/Player";

export default defineComponent({
  name: "PlayerComponent",
  props: {
    player: Object as PropType<Player>,
    current: Boolean,
    onSelectionChanged: Function,
  }
})
</script>

<style scoped>

</style>