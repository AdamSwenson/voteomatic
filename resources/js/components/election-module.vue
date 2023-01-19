<template>

    <div class="election-module main-area">

        <router-tabs></router-tabs>

        <message-area></message-area>

        <!--        <vote-count-alert></vote-count-alert>-->
        <div class="row">
            <div class="col-md-1 col-lg-2"></div>

            <div class="col-md-10 col-lg-8">
                <router-view name="main"></router-view>

                <chair-indicator></chair-indicator>
            </div>

            <div class="col-md-1 col-lg-2"></div>
        </div>

    </div>

</template>

<script>

//The main page for anything
import VotePage from "./main/vote-page";
import Motion from '../models/Motion';
import MeetingMixin from '../mixins/meetingMixin';
import RouterTabs from "./navigation/router-tabs";
import RefreshButton from "./navigation/refresh-button";
import ChairIndicator from "./text-display/chair-indicator";
import NavigationMixin from '../mixins/NavigationMixin';
import ChairMixin from "../mixins/chairMixin";
import MotionSecondModal from "./motions/motion-second-modal";
import MotionInOrderModal from "./motions/motion-in-order-modal";
import ChairMotionSecondModal from "./motions/chair-motion-second-modal";
import MessageArea from "./messaging/message-area";
import VoteCountAlert from "./main/chair/vote-count-alert";
// import ElectionNavigationMixin from "./election/mixins/electionNavigationMixin";

export default {
    name: "election-module",
    components: {
        VoteCountAlert,
        MessageArea,
        ChairMotionSecondModal,
        MotionInOrderModal, MotionSecondModal, ChairIndicator, RefreshButton, RouterTabs, VotePage
    },

    mixins: [MeetingMixin, NavigationMixin, ChairMixin],

    data: function () {
        return {
            isReady: false,
            isSetup: false,
        }
    },

    computed: {},

    methods: {},

    mounted: function () {
        let me = this;

        window.console.log('startup - isElection', window.startData.isElection);

        //We're going to push it to the home tab
        //before loading anything else. That way we both have
        //something open (and not a blank card) and
        //don't send them back to the home tab if they've
        //clicked another tab while things were loading.
        //We did need to load the meeting first so that
        //we can determine whether it is a meeting or election
        // this.forceNavigationToElectionHome();

        //parse data from page and store stuff
        this.$store.dispatch('initializeElection').then(function () {

            window.console.log('voteomatic - election', 'isReady', 159, me.meeting);
        });


    }


}
</script>

<style scoped>
.refresh-area {
    margin-top: 2em;
}
</style>
