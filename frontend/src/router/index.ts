import FileNotFoundView from '@/views/FileNotFoundView.vue';
import HomeView from '@/views/HomeView.vue'
import LogView from '@/views/LogView.vue';
import {createRouter, createWebHistory} from 'vue-router'

function router(baseUri: string) {
    return createRouter({
        history: createWebHistory(baseUri),
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
            },
            {
                path: '/404',
                name: 'file-not-found',
                component: FileNotFoundView
            }
        ]
    });
}

export default router;
