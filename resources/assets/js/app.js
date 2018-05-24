
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
Vue.component('sidebar-component', require('./components/SidebarComponent.vue'));

const store = new Vuex.Store({
    state: {
        totalClients: 0,
        showATC: true,
        showFIR: true,
        searchQuery: '',

        showSidebar: false,
        flightInformation: {
            'image': 'none',
            'flightnr': 'replace-me',
            'departure_airport': 'replace-me',
            'departure_airport_iata': 'replace-me',
            'arrival_airport': 'replace-me',
            'arrival_airport_iata': 'replace-me',
            'travel_percentage': 0,
            'departure_estimated': '--:--',
            'departure_actual': '--:--',
            'arrival_estimated': '--:--',
            'arrival_actual': '--:--',
            'flightplan': 'No flightplan found',
            'aircraft_type': 'Unknown',
            'aircraft_pilot': 'Unknown',
            'aircraft_speed': 0,
            'aircraft_heading': 0,
            'aircraft_altitude': 0
        }
    },
    mutations: {
        setShowATC(state, payload){
            state.showATC = payload;
        },
        setShowFIR(state, payload){
            state.showFIR = payload;
        },
        setSearchQuery(state, payload){
            state.searchQuery = payload;
        }
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