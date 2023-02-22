<script lang="ts">
import {computed, defineComponent} from 'vue'
import {RouterLink, RouterView, useRouter} from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'
import {mapState, useStore} from 'vuex';
import {DndProvider} from "vue3-dnd";
import {HTML5Backend} from "react-dnd-html5-backend";

export default defineComponent({
  components: {
    HelloWorld, RouterLink, RouterView, DndProvider, HTML5Backend
  },
  setup() {
    const store = useStore();
    const router = useRouter();

    if(!store.state.token && router.currentRoute.value.path != "/login") router.push("/login");

    store.watch(
        (state, _) => state.token,
        (newVal, oldVal) => {
          if(!newVal && router.currentRoute.value.path != "/login") router.push("/login");
        }
    )

    return {
      disconnect: () => {
        store.state.user = null;
        store.commit('setToken', null);
        router.push("/login");
      },
      token: computed(() => store.state.token),
      party: computed(() => store.state.party),
      HTML5Backend
    }
  },
});

</script>

<template>
  <DndProvider :backend="HTML5Backend">
    <RouterView />
  </DndProvider>
  <footer class="btm-nav">
    <RouterLink :to="token ? '/' : '/login'">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-1/3 w-1/3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
    </RouterLink>
    <RouterLink v-if="token && party" to="/party">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-1/3 w-1/3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    </RouterLink>
    <RouterLink v-if="token" to="/players">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-1/3 w-1/3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
    </RouterLink>
    <button v-if="token" @click="disconnect()">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-1/3 w-1/3" fill="red" viewBox="0 0 526 526" stroke="none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V256c0 17.7 14.3 32 32 32s32-14.3 32-32V32zM143.5 120.6c13.6-11.3 15.4-31.5 4.1-45.1s-31.5-15.4-45.1-4.1C49.7 115.4 16 181.8 16 256c0 132.5 107.5 240 240 240s240-107.5 240-240c0-74.2-33.8-140.6-86.6-184.6c-13.6-11.3-33.8-9.4-45.1 4.1s-9.4 33.8 4.1 45.1c38.9 32.3 63.5 81 63.5 135.4c0 97.2-78.8 176-176 176s-176-78.8-176-176c0-54.4 24.7-103.1 63.5-135.4z"/></svg>
    </button>
  </footer>
</template>
