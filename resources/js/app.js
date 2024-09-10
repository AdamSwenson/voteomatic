/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from "vue";
window.Vue = Vue;
// window.Vue = require('vue');

require('./bootstrap');


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ API ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
import axios from 'axios'
import VueAxios from 'vue-axios'

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
//
// window.axios.defaults.baseURL = routeRoot;
//
// // This wrapper bind axios to Vue or this if you're using single file component.
Vue.use(VueAxios, axios)


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ ROUTER ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
import VueRouter from 'vue-router'
Vue.use( VueRouter );


// Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// Vue.extend(), or just a component options object.
import  {meetingRoutes} from './routes.client.meeting';
import { electionRoutes } from './routes.client.election';

let routes = _.concat(electionRoutes, meetingRoutes);

// let routes = window.startData.isElection ? electionRoutes : meetingRoutes;
// window.console.log(routes);
// // Create the router instance and pass the `routes` option
export const router = new VueRouter( {
    routes: routes, // short for routes: routes
    base: window.routeRoot
} );



/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ STORE ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
import Vuex from 'vuex'
Vue.use(Vuex)
import store from './store';


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ OTHER VUE ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
import AsyncComputed from 'vue-async-computed'
Vue.use(AsyncComputed)

import wysiwyg from "vue-wysiwyg";
Vue.use(wysiwyg, {  forcePlainTextOnPaste: true,}); // config is optional. more below

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ GLOBAL REG ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */


// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))


// Top level components
Vue.component('voteomatic', require('./components/voteomatic').default);
Vue.component('page-navbar', require('./components/navigation/page-navbar').default);
Vue.component('waitlist', require('./components/waitlist').default);
Vue.component('home-page', require('./components/home-page').default);
Vue.component('event-list-card', require('./components/common/event-list-card').default);

Vue.component('pmode-page-navbar', require('./components/pmode/pmode-page-navbar').default);

//todo DEV TOP LEVEL
Vue.component('results', require('./components/main/motion-results-page').default);
Vue.component('setup-page', require('./components/setup-page').default);
Vue.component('amendment-page', require('./components/main/amendment-page').default);
Vue.component('election-card', require('./components/election/voting/election-card').default);
Vue.component('election-setup-card', require('./components/election/setup/election/election-setup-card').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store: store,
    router : router
});
