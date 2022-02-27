<template>

    <div class="router-tabs" role="navigation">
        <ul class=" nav nav-tabs ">
            <router-tab v-for="r in shownRoutes" :route="r" :key="r.name"></router-tab>

            <!--        <router-link-->
            <!--            v-for="r in routes"-->
            <!--            v-bind:key="r.name"-->
            <!--            tag="li"-->
            <!--            v-bind:style="styling"-->
            <!--            v-bind:active-class="activeClass"-->
            <!--            v-bind:to="r.path"-->
            <!--        >-->
            <!--            &lt;!&ndash;                <li class="nav-item">&ndash;&gt;-->
            <!--            <a class="page-nav nav-link">-->
            <!--                    <span class="icon is-small">-->
            <!--&lt;!&ndash;                        <svg v-bind:class="r.icon" aria-hidden="true">&ndash;&gt;-->
            <!--&lt;!&ndash;                              <use xlink:href="bootstrap-icons.svg#{{r.icon}}"/>&ndash;&gt;-->
            <!--&lt;!&ndash;                        </svg>&ndash;&gt;-->
            <!--&lt;!&ndash;                        <i v-bind:class="r.icon" aria-hidden="true"></i>&ndash;&gt;-->
            <!--                        </span>-->
            <!--                <span>{{ r.label }}</span>-->
            <!--            </a>-->
            <!--        </router-link>-->

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

        shownRoutes: function () {
            let showRoutes = [];
            let me = this;
            _.forEach(this.routes, (r) => {
                // if (r.type === 'election' || r.type === 'all') {
                if (r.name === 'results' || r.name === 'election-results') {
                    if (this.showResultsTab) {
                        showRoutes.push(r);
                    }
                    //nothing happens if showResults isn't true

                } else if (r.name === 'verify' || r.name === 'election-verify') {

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
            // return routes
        },


        // electionRoutes:
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
        },
        isElection: function () {
            return this.$store.getters.isElection;
        },

        // shownRoutes: function () {
        //     // window.console.log('isElection', this.$store.getters.isElection);
        //     if (this.$store.getters.isElection) {
        //         return this.electionRoutes;
        //     }
        //     return this.routes;
        // },

        showVerifyTab: function () {
            return this.$store.getters.getMotionIdsUserVotedUpon.length > 0;
        },


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


    methods: {},


}

</script>
