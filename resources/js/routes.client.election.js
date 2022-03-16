import Vue from 'vue'
//Panes (main container for edit tools)

import store from "./store/index";

import ballotSetupCard from "./components/main/ballot-setup-card";
// import electionCard from "./components/election/election-card";
// import electionSetupCard from "./components/election/setup/election-setup-card";
//import votePage from './components/main/vote-page'
import resultsCard from './components/main/results-card'
// import motionSetup from './components/main/chair/motion-setup'
// import meetingSetup from './components/main/chair/meeting-setup'
import voteVerify from './components/main/vote-verification-page'
import meetingHome from './components/main/meeting-home'
import eventSetupCard from "./components/main/chair/event-setup-card";
import voteCard from "./components/main/vote-card";

import electionVoterHomeCard from './components/election/voter/election-voter-home-card';
import propositionSetupCard from "./components/election/propositions/proposition-setup-card";

import electionAdminCard from './components/election/admin/election-admin-card';
Vue.component('ballot-setup-card', ballotSetupCard);

Vue.component('proposition-setup-card', propositionSetupCard);

// Vue.component('election-card', electionCard);
// Vue.component('election-setup-card', electionSetupCard);
Vue.component(('event-setup-card', eventSetupCard));

Vue.component('meeting-home', meetingHome);

// Vue.component('meeting-setup-page', meetingSetup);
// Vue.component('motion-setup-page', motionSetup);
Vue.component('results-card', resultsCard);
// Vue.component( 'vote-page', votePage );
Vue.component('vote-card', voteCard);
Vue.component('vote-verify', resultsCard);

//dev
Vue.component('election-voter-home-card', electionVoterHomeCard);

Vue.component('election-admin-card', electionAdminCard);


export const electionRoutes = [
    {
        name: 'election-home',
        path: '/election-home',
        label: 'Home',
        components: {main: electionVoterHomeCard},
        get default() {
            if (store.getters.isElection) return true;
            return false;
        },
        props: true,
        adminOnly: false,
        /** List of phases in which this tab is displayed to administrators*/
        electionPhasesAdmin : ['setup', 'voting', 'closed'],
        /** List of phases in which this tab is displayed to voters */
        electionPhasesVoter : ['voting'],

        type: 'election'
    },


    {
        name: 'election-results',
        path: '/election-results',
        icon: "fa fa-comments-o",
        label: "Results",
        components: {main: resultsCard},
        props: true,
        adminOnly: false,

        /** List of phases in which this tab is displayed to administrators*/
        electionPhasesAdmin : [ 'closed', 'results'],
        /** List of phases in which this tab is displayed to voters */
        electionPhasesVoter : [ 'results'],

        type: 'election'
    },


//Not actually different, just needed a different name to prevent collision
    {
        name: 'election-verify',
        path: '/verify',
        icon: "fa fa-check",
        label: "Verify votes",
        components: {main: voteVerify},
        props: true,
        adminOnly: false,

        /** List of phases in which this tab is displayed to administrators*/
        electionPhasesAdmin : ['setup', 'voting',  'closed', 'results'],
        /** List of phases in which this tab is displayed to voters */
        electionPhasesVoter : ['voting', 'closed', 'results'],

        type : 'election'
    },


    {
        name: 'setup-offices',
        path: '/setup-offices',
        icon: "fa fa-bar-chart",
        label : "Setup offices",
        // get label() {
        //     if (store.getters.isElection) return "Setup offices";
        //     return "Make motion";
        // },
        components: {main: ballotSetupCard},
        props: true,
        adminOnly: true,

        /** List of phases in which this tab is displayed to administrators*/
        electionPhasesAdmin : ['setup', 'voting', 'closed', 'results'],
        /** List of phases in which this tab is displayed to voters */
        electionPhasesVoter : [],

        type : 'election'
    },


    {
        name: 'setup-props',
        path: '/setup-props',
        icon: "fa fa-bar-chart",
        label: 'Setup propositions',
        components: {main: propositionSetupCard},
        props: true,
        // adminOnly: true,
        adminOnly: true,

        /** List of phases in which this tab is displayed to administrators*/
        electionPhasesAdmin : ['setup', 'voting', 'closed', 'results'],
        /** List of phases in which this tab is displayed to voters */
        electionPhasesVoter : [],

        type : 'election'
    },



    {
        name: 'setup-election',
        path: '/setup',
        get label() {
            return "Setup election";
            // if (store.getters.isElection) return "Setup election";
            // return "Setup meeting";
        },
        components: {main: eventSetupCard},
        props: true,
        adminOnly: true,

        /** List of phases in which this tab is displayed to administrators*/
        electionPhasesAdmin : ['setup', 'voting', 'closed', 'results'],
        /** List of phases in which this tab is displayed to voters */
        electionPhasesVoter : [],

        type : 'election'
    },


    {
        name: 'admin',
        path: '/admin',
        label : 'Admin',
        components: {main: electionAdminCard},
        props: true,
        adminOnly: true,

        /** List of phases in which this tab is displayed to administrators*/
        electionPhasesAdmin : ['setup', 'voting', 'closed', 'results'],
        /** List of phases in which this tab is displayed to voters */
        electionPhasesVoter : [],

        type : 'election'
    },




    // {
    //     name: 'meeting',
    //     path: '/meeting',
    //     icon: "bi bi-sunglasses",
    //     label: "Setup meeting",
    //     components: {main: meetingSetup},
    //     props: true,
    //     adminOnly: true
    // },

    // {
    //     name: 'election',
    //     path: '/election',
    //     label: 'Election',
    //     components: {main: electionCard},
    //     props: true,
    //     adminOnly: false
    // },

    // {
    //     name: 'election-setup',
    //     path: '/election-setup',
    //     label: 'Election setup',
    //     components: {main: electionSetupCard},
    //     props: true,
    //     adminOnly: true
    // },


];

// export default {
//     routes
// }
