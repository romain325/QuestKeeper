import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import ConnectionView from "@/views/ConnectionView.vue";
import PlayersView from "@/views/PlayersView.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue')
    },
    {
      path: '/login',
      name: 'login',
      component: ConnectionView
    },
    {
      path: '/players',
      name: 'players',
      component: () => import('../views/PlayersView.vue'),
    },
    {
      path: '/party',
      name: 'party',
      component: () => import('../views/PlayersView.vue')
    },
    {
      path: '/players/create',
      name: 'create',
      component: () => import('../views/CreatePlayerView.vue')
    }
  ]
})

export default router
