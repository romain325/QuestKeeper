import { createApp } from 'vue'
import App from './App.vue'
import './assets/main.css'
import {createStore} from "vuex";

const app = createApp(App)
const store = createStore({
    state() {
        return {
            token: null as string|null
        }
    },
    mutations: {
        setToken(state, value) {
            state.token = value;
        },
    }
})

app.use(store)

import router from './router'
app.use(router)

app.mount('#app')
