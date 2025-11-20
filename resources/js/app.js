import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';

// Import Bootstrap JS (optional - for components like modals, dropdowns)
import * as bootstrap from 'bootstrap';

const app = createApp(App);

// Make Bootstrap available globally in your Vue app if needed
app.config.globalProperties.$bootstrap = bootstrap;

app.mount('#app');
//createApp(App).mount('#app');
