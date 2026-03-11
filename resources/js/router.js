/*
 * Copyright (c) 2025. Adam Swenson
 */

import { createRouter, createWebHistory,  createWebHashHistory } from 'vue-router'

// Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// Vue.extend(), or just a component options object.
import  {meetingRoutes} from './routes.client.meeting';
import { electionRoutes } from './routes.client.election';

let routes = _.concat(electionRoutes, meetingRoutes);

//set base
const history =createWebHashHistory();


export default createRouter( {
    routes, // short for routes: routes
    history,
    // base: window.routeRoot

    //Added VOT-335
    //NB, this only affects clicking the main tabs at the top so doesn't resolve VOT-335
    scrollBehavior(to, from, savedPosition) {
        // always scroll to top
        return { top: 0, behavior: 'smooth' }
    },
} );
