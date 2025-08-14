import { createApp } from 'vue';
import ProductSearch from './components/ProductSearch.vue';

const app = createApp({});

// Register component globally
app.component('product-search', ProductSearch);

app.mount('#app'); // Make sure you have a div with id="app" in your blade file