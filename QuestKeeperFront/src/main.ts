import { createApp } from 'vue'
import App from './App.vue'
import './assets/main.css'
import {createStore} from "vuex";
import router from './router'
import {DndProvider} from "vue3-dnd";

const app = createApp(App)
const store = createStore({
    state() {
        return {
            token: null as string|null,
            party: null as string|null,
            user: null as string|null
        }
    },
    mutations: {
        setToken(state, value) {
            state.token = value;
        },
    }
})

app.use(store)

app.use(router)


app.mount('#app')
