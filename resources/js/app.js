/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import { createApp } from 'vue'


require('./bootstrap');

// import Vue from "vue";
// window.Vue = Vue;
// window.Vue = require('vue');

const app = createApp({
    el: '#app',
});


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ API ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
import axios from 'axios'
import VueAxios from 'vue-axios'

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
app.use(VueAxios, axios);
app.provide('axios', app.config.globalProperties.axios)  // provide 'axios'


/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ ROUTER ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
// import { createRouter, createWebHistory,  createWebHashHistory } from 'vue-router'
//
// // Define some routes
// // Each route should map to a component. The "component" can
// // either be an actual component constructor created via
// // Vue.extend(), or just a component options object.
// import  {meetingRoutes} from './routes.client.meeting';
// import { electionRoutes } from './routes.client.election';
//
// let routes = _.concat(electionRoutes, meetingRoutes);
//
// //set base
// const history =createWebHashHistory();
//
//
// const router = createRouter( {
//     routes, // short for routes: routes
//     history,
//     // base: window.routeRoot
// } );

import router from "./router";
app.use(router);

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ STORE ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */

import store from './store';
app.use(store);

/* ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ OTHER VUE ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ */
import AsyncComputed from 'vue-async-computed'
app.use(AsyncComputed)

//dev This is causing trouble for VOT-99
// import wysiwyg from "vue-wysiwyg";
// app.use(wysiwyg, {  forcePlainTextOnPaste: true,}); // config is optional. more below

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
app.component('voteomatic', require('./components/voteomatic').default);
app.component('page-navbar', require('./components/navigation/page-navbar').default);
app.component('waitlist', require('./components/waitlist').default);
app.component('home-page', require('./components/home-page').default);
app.component('event-list-card', require('./components/common/event-list-card').default);

app.component('pmode-page-navbar', require('./components/pmode/pmode-page-navbar').default);

//todo DEV TOP LEVEL
// app.component('results', require('./components/main/motion-results-page').default);
// app.component('setup-page', require('./components/setup-page').default);
// app.component('amendment-page', require('./components/main/amendment-page').default);
// app.component('election-card', require('./components/election/voting/election-card').default);
// app.component('election-setup-card', require('./components/election/setup/election/election-setup-card').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

app.mount('#app');

// const app = new Vue({
//     el: '#app',
//     store: store,
//     router : router
// });
