import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-icons/font/bootstrap-icons.css';
import './assets/main.scss'
import axios from 'axios';
import {createPinia} from 'pinia'

import {createApp} from 'vue'

import LogViewer from './LogViewer.vue'
import router from './router'

const baseUri = document.head.querySelector<HTMLMetaElement>('[name=base-uri]')!.content;

// set axios base url
axios.defaults.baseURL = baseUri;

const app = createApp(LogViewer);

app.use(createPinia());
app.use(router(baseUri));
app.mount('#log-viewer');
