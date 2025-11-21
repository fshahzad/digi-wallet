import {createRouter, createWebHashHistory} from 'vue-router';
import Home from '../pages/Home.vue';
import Wallet from '../pages/Wallet.vue';

const routes = [
    { path: '/', name: 'Home', component: Home },
    { path: '/wallet', name: 'Wallet', component: Wallet },
];

const router = createRouter({
    history: createWebHashHistory(import.meta.env.APP_URL),
    routes
});

export default router;
