import './bootstrap';
import * as bootstrap from 'bootstrap';
import { createApp } from 'vue';
import App from '../../src/App.vue';
import router from '../../src/router';
import useAuth from '../../src/useAuth';

const app = createApp(App);

app.use(router);

// Make Bootstrap available globally in Vue app
app.config.globalProperties.$bootstrap = bootstrap;

useAuth.attempt().then(() => {
    //console.log('User authenticated:', useAuth.authenticated);
    //console.log( useAuth.user );
    app.mount('#app');
});


