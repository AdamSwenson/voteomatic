<template>
    <div class="meeting-display card">

        <div class="card-header">
            <h4 class="card-title">Meeting information</h4>
        </div>

        <div class="card-body meeting-display">

            <h5 class="card-title text-start">{{ meetingName }}</h5>

            <h5 class="card-subtitle text-start">{{ meetingDate }}</h5>

            <meeting-url-display></meeting-url-display>


            <p class="text-secondary">[ Optional short description of meeting will be here ]</p>

            <p class="text-secondary">[ Optional agenda will be here ]</p>

        </div>

<!--        <div class="card-footer">-->
<!--            <div class="card-text">NB, ignore the url above if you do not have the voteomatic configured as an app in a canvas class.</div>-->
<!--        </div>-->

    </div>


</template>

<script>
import MeetingMixin from '../../mixins/meetingMixin';
import ChairMixin from "../../mixins/chairMixin";
import MeetingUrlDisplay from "./meeting-url-display";

// import Payload from "../../models/Payload";

export default {
    name: "meeting-display-card",
    components: {MeetingUrlDisplay},
    props: [],

    mixins: [MeetingMixin, ChairMixin],

    data: function () {
        return {
            // linkBase: "https://voteomatic.com/lti-entry/"
        }
    },

    asyncComputed: {

        /**
         * The link that the user will enter into
         * canvas
         */
        // meetingLink: {
        //     get: function () {
        //         return this.linkBase + this.meeting.id;
        //     },
        //     default: false
        // },

        // meetingDate: {
        //     get: function () {
        //         try {
        //             return this.meeting.date;
        //         } catch (e) {
        //             return ''
        //         }
        //     },
        //
        // },
        //
        //
        // meetingName: {
        //     get: function () {
        //         try {
        //             return this.meeting.name;
        //         } catch (e) {
        //             return ''
        //         }
        //     },
        //
        // },

    },


    computed: {},
    methods: {}

}
</script>

<style scoped>

</style>
