import './bootstrap';

import { createApp } from 'vue';
import App from '../../src/App.vue';

// Import Bootstrap JS (optional - for components like modals, dropdowns)
import * as bootstrap from 'bootstrap';

const appName = import.meta.env.VITE_APP_NAME || '';
const app = createApp(App);

// Make Bootstrap available globally in your Vue app if needed
app.config.globalProperties.$bootstrap = bootstrap;

app.mount('#app');
//createApp(App).mount('#app');
