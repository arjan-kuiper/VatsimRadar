
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vuex from 'vuex'
require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.use(Vuex);
Vue.component('map-component', require('./components/MapComponent.vue'));
Vue.component('navbar-component', require('./components/NavbarComponent.vue'));

const store = new Vuex.Store({
    state: {
        totalClients: 0
    }
});
const navbarData = new Vue({
    el: '#navbar-data',
    store
});
const app = new Vue({
    el: '#app',
    store
});
