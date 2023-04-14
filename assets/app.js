import './styles/app.css';
import { createApp } from 'vue';

import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

import {createRouter, createWebHistory} from 'vue-router'
import FavoriteFruits from "./pages/FavoriteFruits.vue";
import Fruits from "./pages/Fruits.vue";
import App from "./pages/App.vue";

const vuetify = createVuetify({
    components,
    directives,
})

const routes = [
    { path: '/', component: Fruits },
    { path: '/favorites', component: FavoriteFruits },
]
const router = createRouter({
    history: createWebHistory(),
    routes,
})
createApp(App).use(vuetify).use(router).mount('#app');
