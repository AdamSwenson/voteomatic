<template>

    <div class="meeting-module main-area">

        <message-area></message-area>
        <vote-count-alert></vote-count-alert>

        <div class="row">
            <div class="col-xl-1 col-xxl-2"></div>

<!--            <div class="col-lg-1 col-xl-2"></div>-->
            <div class="col-xl-10 col-xxl-8">

<!--            <div class="col-lg-10 col-xl-8">-->
<!--            <div class="col-md-10 col-lg-8">-->
                <router-tabs></router-tabs>
                <router-view name="main"></router-view>
            </div>

            <div class="col-xl-1 col-xxl-2"></div>

<!--            <div class="col-lg-1 col-xl-2"></div>-->

<!--            <div class="col-md-1 col-lg-2"></div>-->
        </div>

        <chair-indicator></chair-indicator>

        <motion-in-order-modal v-if="isChair"></motion-in-order-modal>
        <chair-motion-second-modal v-if="isChair"></chair-motion-second-modal>
        <motion-second-modal v-if="! isChair"></motion-second-modal>

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

export default {
    name: "meeting-module",
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

    computed: {


        //     //the motion being voted upon
        //     motion: function () {
        //         //todo convert to
        //
        //         let d = window.startData.motion;
        //         let m = new Motion(d);
        //
        //         // let m = new Motion(d.id, d.content, d.description, d.requires);
        //         return m
        //     }
    },

    mounted: function () {
        let me = this;

        window.console.log('startup isElection', window.startData.isElection);

        //We're going to push it to the home tab
        //before loading anything else. That way we both have
        //something open (and not a blank card) and
        //don't send them back to the home tab if they've
        //clicked another tab while things were loading.
        //We did need to load the meeting first so that
        //we can determine whether it is a meeting or election

        this.$store.dispatch('forceNavigationToHome');

        //parse data from page and store stuff
        let p = this.$store.dispatch('initialize');
        p.then(function () {
            // me.$router.push('meeting-home');

            // if()

            window.console.log('voteomatic', 'isReady', 159, me.isReady);
        });

        // me.$router.push('meeting-home');

    }


}
</script>

<style scoped>

/*.refresh-area {*/
/*    margin-top: 2em;*/
/*}*/
</style>
