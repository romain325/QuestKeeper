<template>
  <div class="flex flex-wrap w-full h-full">
    <div class="w-1/2 h-full">
    <!--admin: drag & drop + item, player: profile -->
      a
    </div>
    <div class="w-1/2 h-full">
      <div class="w-full h-1/2">
        <!-- admin: interactive team, player: not interactive team -->
        a
      </div>
      <div class="w-full h-1/2">
        <!-- dice roller -->
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
// grid grid-flow-col
export default defineComponent({
  name: "PartyView",
  components: {DiceRoller},
  beforeCreate() {
    const store = useStore();
    if(!store.state.party){
      const router = useRouter();
      router.push("/");
    }
  }
})
</script>

<style scoped>

</style>