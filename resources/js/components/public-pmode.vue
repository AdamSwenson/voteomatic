<template>

    <div class="public-pmode">
        <message-area></message-area>

        <pmode-home></pmode-home>


    </div>

</template>

<script>

//The main page for anything
import MeetingMixin from '../mixins/meetingMixin';
import NavigationMixin from '../mixins/NavigationMixin';
import ChairMixin from "../mixins/chairMixin";
import MessageArea from "./messaging/message-area";
import PmodeHome from "./pmode/pmode-home";

export default {
    name: "public-pmode",
    components: {
        PmodeHome,
        MessageArea,

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

        // window.console.log('startup isElection', window.startData.isElection);

        //We're going to push it to the home tab
        //before loading anything else. That way we both have
        //something open (and not a blank card) and
        //don't send them back to the home tab if they've
        //clicked another tab while things were loading.
        //We did need to load the meeting first so that
        //we can determine whether it is a meeting or election

        // this.$store.dispatch('forceNavigationToHome');

        //parse data from page and store stuff
        let p = this.$store.dispatch('initializePublicPmode');
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
.refresh-area {
    margin-top: 2em;
}
</style>
