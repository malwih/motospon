import './bootstrap';
import Alpine from 'alpinejs';

// Hanya impor SortSelector jika diperlukan
let SortSelector = null;
try {
    SortSelector = require('./components/SortSelector.vue').default;
} catch (e) {
    console.warn('SortSelector.vue not found, skipping...');
}

// Inisialisasi Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Jika masih menggunakan Vue untuk komponen tertentu
if (typeof window.Vue === 'undefined') {
    window.Vue = require('vue');
    window.Vue.component('sort-selector', SortSelector);
    
    new Vue({
        el: '#app',
    });
}