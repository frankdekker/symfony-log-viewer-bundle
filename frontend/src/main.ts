import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-icons/font/bootstrap-icons.css';
import './assets/main.scss'
import axios from 'axios';

import {createApp} from 'vue'
import {createPinia} from 'pinia'

import App from './App.vue'
import router from './router'

// set axios base url
axios.defaults.baseURL = document.head.querySelector<HTMLMetaElement>('[name=base-uri]')!.content;

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
