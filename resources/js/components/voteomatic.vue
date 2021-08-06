<template>

    <div class="voteomatic">

        <!--        <div class="row ">-->
        <!--            <div class="col-8">-->
        <router-tabs></router-tabs>
        <!--            </div>-->
        <!--            <div class="text-right refresh-area">-->
        <!--                <refresh-button></refresh-button>-->
        <!--        </div>-->
<message-area></message-area>

        <router-view name="main"></router-view>

        <div class="text-center refresh-area">

            <refresh-button></refresh-button>
        </div>

        <chair-indicator></chair-indicator>

        <!--        <svg class="bi" width="32" height="32" fill="currentColor">-->
        <!--            <use xlink:href="bootstrap-icons.svg#heart-fill"/>-->
        <!--        </svg>-->

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

export default {
    name: "voteomatic",
    components: {
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

        //We're going to push it to the home tab
        //before loading anything. That way we both have
        //something open (and not a blank card) and
        //don't send them back to the home tab if they've
        //clicked another tab while things were loading.
        me.$router.push('meeting-home');


        //parse data from page and store stuff
        let p = this.$store.dispatch('initialize');
        p.then(function () {
            // me.$router.push('meeting-home');

            window.console.log('voteomatic', 'isReady', 159, me.isReady);
        });

        // me.$router.push('meeting-home');

    }


}
</script>

<style scoped>
.refresh-area {
    margin-top: 2em;
}
</style>
