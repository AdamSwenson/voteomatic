import Vue from 'vue'
//Panes (main container for edit tools)
import votePage from './components/main/vote-page'
import resultsPage from './components/main/results-page'
import motionSetup from './components/main/chair/motion-setup'
import meetingSetup from './components/main/chair/meeting-setup'
import voteVerify from './components/main/vote-verification-page'
import meetingHome from './components/main/meeting-home'

Vue.component( 'vote-page', votePage );
Vue.component('results-page', resultsPage);
Vue.component('motion-setup-page', motionSetup);
Vue.component('meeting-setup-page', meetingSetup);
Vue.component('vote-verify', resultsPage);
Vue.component('meeting-home', meetingHome);

export const routes =  [
    {
        name: 'home',
        path: '/meeting-home',
        icon: "fa fa-book",
        label: "Home",
        components: { main:  meetingHome},
        default: true,
        props:  true,
        adminOnly : false

    },


    {
        name: 'vote',
        path: '/vote',
        icon: "fa fa-pencil",
        label: "Vote",
        components: {main: votePage},
        props: true,
        adminOnly: false
    },


    {
        name: 'verify',
        path: '/verify',
        icon: "fa fa-check",
        label: "Verify your vote",
        components: { main:  voteVerify},
        props:  true,
        adminOnly : false
    },


    {
        name: 'results',
        path: '/results'  ,
        icon: "fa fa-comments-o",
        label: "Results",
        components: { main: resultsPage },
        props:  true,
        adminOnly : false
    },

    {
        name: 'motion',
        path: '/motion',
        icon: "fa fa-bar-chart",
        label: "Create motion",
        components: {main: motionSetup},
        props: true,
        adminOnly : true,

    },

    {
        name: 'meeting',
        path: '/meeting',
        icon: "bi bi-sunglasses",
        label: "Create meeting",
        components: { main:  meetingSetup},
        props:  true,
        adminOnly : true
    },


    // {
    //     name: 'notes',
    //     path: this.routeToNotes,
    //     icon: "fa fa-sticky-note-o",
    //     label: "Notes"
    // },


//grading
//     {
//         path: '/',
//         components: { questionPanelArea: questionPanel },
//         props:  true  //{default: true}
//     }, //props: (route) => {return route.index;}},
];

// export default {
//     routes
// }
