<template>

    <div class="router-tabs" role="navigation">
        <ul class=" nav nav-tabs ">
            <router-tab v-for="r in shownRoutes" :route="r" :key="r.name"></router-tab>


        </ul>
    </div>


</template>

<style lang="scss">
.item-card-navigation-tabs {

}


</style>
<script>

import {meetingRoutes} from '../../routes.client.meeting';
import {electionRoutes} from '../../routes.client.election';
import RouterTab from "./router-tab";
import store from "../../store";
import MeetingMixin from "../../mixins/meetingMixin";
import {isReadyToRock} from "../../utilities/readiness.utilities";

/**
 * Page navigation tabs
 * .
 */
export default {
    components: {RouterTab},
    props: [],


    mixins: [MeetingMixin],

    data: function () {
        return {

            adminOnly: [],

            activeClass: 'active',
            styling: 'nav-item',

            identifier: 'page-nav-tabs',

        };
    },

    asyncComputed: {
        isAdmin: {
            get: function () {
                return this.$store.getters.getIsAdmin;
            },
            default: false
        },
        // },
        //
        //
        // computed: {

        electionTabs: function () {
            let showRoutes = [];
            let me = this;
            if (!isReadyToRock(this.meeting)) return showRoutes;

            let electionRoutes = this.routes.filter((r) => {
                return r.type === 'election';
            });

            _.forEach(electionRoutes, (r) => {
                if (me.isAdmin && r.electionPhasesAdmin.indexOf(me.meeting.electionPhase) > -1) {
                    showRoutes.push(r);

                } else if (!me.isAdmin && r.electionPhasesVoter.indexOf(me.meeting.electionPhase) > -1) {
                    showRoutes.push(r);

                }

            });
            return showRoutes;

        },

        meetingTabs: function () {
            let showRoutes = [];
            let me = this;
            if (!isReadyToRock(this.meeting)) return showRoutes;

            let meetingRoutes = this.routes.filter((r) => {
                return r.type === 'meeting' || r.type === 'all';
            });

            _.forEach(meetingRoutes, (r) => {

                // if (r.type === 'election' || r.type === 'all') {
                if (r.name === 'results') { //|| r.name === 'election-results') {
                    if (this.showResultsTab) {
                        showRoutes.push(r);
                    }
                    //nothing happens if showResults isn't true

                } else if (r.name === 'verify') { // || r.name === 'election-verify') {

                    if (this.showVerifyTab) {
                        showRoutes.push(r);
                    }

                }
                //So it's neither the results nor the verify tab
                else {
                    if (r.adminOnly) {
                        if (me.isAdmin) {
                            showRoutes.push(r);
                        }
                    } else {
                        //Everything else gets pushed in
                        showRoutes.push(r);
                    }
                    // }
                }
            });
            return showRoutes;

        },

        shownRoutes: function () {
            if (!isReadyToRock(this.meeting)) return [];

            if (this.meeting.type === 'election'){
this.filterToElectionRoutes();
                return this.electionTabs;

            }
            return this.meetingTabs;
            //
            //
            // let showRoutes = [];
            // let me = this;
            // _.forEach(this.routes, (r) => {
            //
            //
            //     // if (r.type === 'election' || r.type === 'all') {
            //     if (r.name === 'results' || r.name === 'election-results') {
            //         if (this.showResultsTab) {
            //             showRoutes.push(r);
            //         }
            //         //nothing happens if showResults isn't true
            //
            //     } else if (r.name === 'verify' || r.name === 'election-verify') {
            //
            //         if (this.showVerifyTab) {
            //             showRoutes.push(r);
            //         }
            //
            //     }
            //     //So it's neither the results nor the verify tab
            //     else {
            //         if (r.adminOnly) {
            //             if (me.isAdmin) {
            //                 showRoutes.push(r);
            //             }
            //         } else {
            //             //Everything else gets pushed in
            //             showRoutes.push(r);
            //         }
            //         // }
            //     }
            //
            // });
            //
            // return showRoutes;
            // return routes
        }
        ,


// electionRoutes
// :
//     function () {
//         let showRoutes = [];
//         let me = this;
//         _.forEach(routes, (r) => {
//             if (r.type === 'election' || r.type === 'all') {
//                 if(r.name === 'election-results' && this.showResultsTab){
//                     showRoutes.push(r);
//                 }
//                 else if(r.name === 'verify' && this.showVerifyTab){
//                     showRoutes.push(r);
//                 }
//                 else {
//                     if (r.adminOnly) {
//                         if (me.isAdmin) {
//                             showRoutes.push(r);
//                         }
//                     } else {
//                         showRoutes.push(r);
//                     }
//                 }
//             }
//         });
//
//         return showRoutes;
//         // return routes
//     },

        routes: function () {
            if (this.isElection) return electionRoutes;

            return meetingRoutes;


            //
            // let showRoutes = [];
            // let me = this;
            // _.forEach(routes, (r) => {
            //     if (r.type === 'meeting' || r.type === 'all') {
            //         if (r.adminOnly) {
            //             if (me.isAdmin) {
            //                 showRoutes.push(r);
            //             }
            //         } else {
            //             showRoutes.push(r);
            //         }
            //     }
            // });
            //
            // return showRoutes;
            // return routes
        }
        ,
        isElection: function () {
            return this.$store.getters.isElection;
        }
        ,

// shownRoutes: function () {
//     // window.console.log('isElection', this.$store.getters.isElection);
//     if (this.$store.getters.isElection) {
//         return this.electionRoutes;
//     }
//     return this.routes;
// },

        showVerifyTab: function () {
            return this.$store.getters.getMotionIdsUserVotedUpon.length > 0;
        }
        ,


        showResultsTab: function () {
            if (this.isElection) {
                if (!isReadyToRock(this.meeting)) return false;

                //dev Eventually this should be instead controlled by a 'results available' prop
                return this.meeting.isComplete === true;
            }

            //dev If need to do for regular meeting, that check goes here

            return true;
        }
    },
mounted() {
    // this.filterToElectionRoutes();

},


    methods: {

        filterToElectionRoutes: function () {

            //
            // window.console.log('before', this.$router.getRoutes());
            //
            // let me = this;
            // _.forEach(this.routes, (r) => {
            //     if (r.type === 'meeting') {
            //         window.console.log('found', r);
            //         let j = me.$router.addRoute(r);
            //         j.removeRoute();
            //     }
            //     ;
            // });
            //
            // window.console.log(this.$router.getRoutes());

        }
        ,
    }

}

</script>
