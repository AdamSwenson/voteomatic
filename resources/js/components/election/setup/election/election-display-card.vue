<template>
    <div class="election-display card">

        <div class="card-header">
            <h4 class="card-title">Election information</h4>
        </div>

        <div class="card-body election-display">

            <h5 class="card-title text-start">{{ meetingName }}</h5>

            <h5 class="card-subtitle text-start">{{ meetingDate }}</h5>

            <meeting-url-display></meeting-url-display>


            <p class="text-secondary">[ Optional short description of election will be here ]</p>

            <!--            <p class="text-secondary">[ Optional agenda will be here ]</p>-->

        </div>

        <!--        <div class="card-footer">-->
        <!--            <div class="card-text">NB, this page doesn't do anything useful unless-->
        <!--                you have the the voteomatic configured as an app in your own canvas class.</div>-->
        <!--        </div>-->

    </div>


</template>

<script>
import MeetingMixin from '../../../../mixins/meetingMixin';
import ChairMixin from "../../../../mixins/chairMixin";
import MeetingUrlDisplay from "../../../meetings/meeting-url-display";

// import Payload from "../../models/Payload";

export default {
    name: "election-display-card",
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
        // electionLink: {
        //     get: function () {
        //         return this.linkBase + this.election.id;
        //     },
        //     default: false
        // },

        // electionDate: {
        //     get: function () {
        //         try {
        //             return this.election.date;
        //         } catch (e) {
        //             return ''
        //         }
        //     },
        //
        // },
        //
        //
        // electionName: {
        //     get: function () {
        //         try {
        //             return this.election.name;
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
