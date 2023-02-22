<template>
  <div class="tooltip tooltip-bottom m-2" :data-tip="item.name + '\n' + item.description"
       :ref="drag"
  >
    <div class="avatar">
      <div class="w-16 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2">
        <img src="https://media.discordapp.net/attachments/1067816772000022609/1068482857565171742/Illustration_sans_titre.png?width=504&height=504" />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import type {PropType} from "vue";
import type Item from "@/models/Item";
import {useDrag} from "vue3-dnd";

export default defineComponent({
  name: "ItemDisplay",
  props: {
    item: Object as PropType<Item>,
    drag: {
      type: Boolean,
      default: false
    }
  },
  setup(props : any) {
    console.log(props)
    const [collectedDrops, drag]  = useDrag(() => ({
      type: props.drag ? 'ITEM' : 'UNAUTHORIZED',
      item: { id: props.item.id }
    }));
    return {
      drag
    }
  }
})
</script>

<style scoped>

</style>