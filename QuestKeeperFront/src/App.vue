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
      <svg xmlns="http://www.w3.org/2000/svg" class="h-1/2 w-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
    </RouterLink>
    <RouterLink v-if="token && party" to="/party">
      <svg viewBox="0 0 24 24" class="h-1/2 w-1/2" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor"><g stroke-width="2"></g><g stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.25 7.75H16.255M16.25 11.75H16.255M16.25 16.25H16.255M7.75 7.75H7.755M7.75 11.75H7.755M7.75 16.25H7.755M7.8 21H16.2C17.8802 21 18.7202 21 19.362 20.673C19.9265 20.3854 20.3854 19.9265 20.673 19.362C21 18.7202 21 17.8802 21 16.2V7.8C21 6.11984 21 5.27976 20.673 4.63803C20.3854 4.07354 19.9265 3.6146 19.362 3.32698C18.7202 3 17.8802 3 16.2 3H7.8C6.11984 3 5.27976 3 4.63803 3.32698C4.07354 3.6146 3.6146 4.07354 3.32698 4.63803C3 5.27976 3 6.11984 3 7.8V16.2C3 17.8802 3 18.7202 3.32698 19.362C3.6146 19.9265 4.07354 20.3854 4.63803 20.673C5.27976 21 6.11984 21 7.8 21ZM16.5 7.75C16.5 7.88807 16.3881 8 16.25 8C16.1119 8 16 7.88807 16 7.75C16 7.61193 16.1119 7.5 16.25 7.5C16.3881 7.5 16.5 7.61193 16.5 7.75ZM16.5 11.75C16.5 11.8881 16.3881 12 16.25 12C16.1119 12 16 11.8881 16 11.75C16 11.6119 16.1119 11.5 16.25 11.5C16.3881 11.5 16.5 11.6119 16.5 11.75ZM16.5 16.25C16.5 16.3881 16.3881 16.5 16.25 16.5C16.1119 16.5 16 16.3881 16 16.25C16 16.1119 16.1119 16 16.25 16C16.3881 16 16.5 16.1119 16.5 16.25ZM8 7.75C8 7.88807 7.88807 8 7.75 8C7.61193 8 7.5 7.88807 7.5 7.75C7.5 7.61193 7.61193 7.5 7.75 7.5C7.88807 7.5 8 7.61193 8 7.75ZM8 11.75C8 11.8881 7.88807 12 7.75 12C7.61193 12 7.5 11.8881 7.5 11.75C7.5 11.6119 7.61193 11.5 7.75 11.5C7.88807 11.5 8 11.6119 8 11.75ZM8 16.25C8 16.3881 7.88807 16.5 7.75 16.5C7.61193 16.5 7.5 16.3881 7.5 16.25C7.5 16.1119 7.61193 16 7.75 16C7.88807 16 8 16.1119 8 16.25Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg>
    </RouterLink>
    <RouterLink v-if="token" to="/players">
      <svg viewBox="0 0 24 24" fill="none" class="h-1/2 w-1/2" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path d="M16.719 19.7519L16.0785 14.6279C15.8908 13.1266 14.6146 12 13.1017 12H12H10.8983C9.38538 12 8.10917 13.1266 7.92151 14.6279L7.28101 19.7519C7.1318 20.9456 8.06257 22 9.26556 22H12H14.7344C15.9374 22 16.8682 20.9456 16.719 19.7519Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <circle cx="12" cy="5" r="3" stroke="currentColor" stroke-width="2"></circle> <circle cx="4" cy="9" r="2" stroke="currentColor" stroke-width="2"></circle> <circle cx="20" cy="9" r="2" stroke="currentColor" stroke-width="2"></circle> <path d="M4 14H3.69425C2.71658 14 1.8822 14.7068 1.72147 15.6712L1.38813 17.6712C1.18496 18.8903 2.12504 20 3.36092 20H7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> <path d="M20 14H20.3057C21.2834 14 22.1178 14.7068 22.2785 15.6712L22.6119 17.6712C22.815 18.8903 21.8751 20 20.6392 20C19.4775 20 18.0952 20 17 20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path> </g></svg> 
    </RouterLink>
    <button v-if="token" @click="disconnect()">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-1/2 w-1/2" fill="red" viewBox="0 0 526 526" stroke="none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32V256c0 17.7 14.3 32 32 32s32-14.3 32-32V32zM143.5 120.6c13.6-11.3 15.4-31.5 4.1-45.1s-31.5-15.4-45.1-4.1C49.7 115.4 16 181.8 16 256c0 132.5 107.5 240 240 240s240-107.5 240-240c0-74.2-33.8-140.6-86.6-184.6c-13.6-11.3-33.8-9.4-45.1 4.1s-9.4 33.8 4.1 45.1c38.9 32.3 63.5 81 63.5 135.4c0 97.2-78.8 176-176 176s-176-78.8-176-176c0-54.4 24.7-103.1 63.5-135.4z"/></svg>
    </button>
  </footer>
</template>
