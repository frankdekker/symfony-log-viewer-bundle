import HomeView from '@/views/HomeView.vue'
import LogView from '@/views/LogView.vue';
import {createRouter, createWebHistory} from 'vue-router'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView
        },
        {
            path: '/log',
            name: 'log',
            component: LogView
        }
    ]
})

export default router
