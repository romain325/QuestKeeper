<template>
  <div class="flex flex-col items-center justify-center w-full h-full">
    <div class="btn-group justify-center w-full">
      <button :class="'btn ' + ( dice === i ? 'btn-active' : '' )" v-for="i of [2,4,6,8,10,12,20]" @click="selectRoll(i)">{{i}}</button>
    </div>
    <span class="countdown font-mono text-6xl">
      <span :style="'--value:' + content + ';'"></span>
    </span>
    <button class="btn btn-primary btn-lg" @click="rollDice">ROLL</button>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
  name: "DiceRoller",
  methods: {
    selectRoll(i: number) {
      this.dice = i;
    },
    rollDice() {
      this.content = Math.floor(Math.random() * this.dice) + 1;
    }
  },
  data() {
    return {
      dice: 2,
      content: 0
    }
  }
});
</script>

<style scoped>

</style>