<template>
    <div class="meeting-display card">

        <div class="card-header">
            <h4 class="card-title">Meeting information</h4>
        </div>

        <div class="card-body meeting-display">

            <h5 class="card-title text-left">{{ meetingName }}</h5>

            <h5 class="card-subtitle text-left">{{ meetingDate }}</h5>

            <div class="meeting-link text-left mt-4" v-if="isChair">
                <h5 class="card-subtitle user-select-all">{{ meetingLink }}</h5>
                <p class="text-muted ">Use this link in creating the Canvas assignment.</p>
            </div>

            <p class="text-secondary">[ Optional short description of meeting will be here ]</p>

            <p class="text-secondary">[ Optional agenda will be here ]</p>

        </div>

    </div>


</template>

<script>
import MeetingMixin from '../../mixins/meetingMixin';
import ChairMixin from "../../mixins/chairMixin";

// import Payload from "../../models/Payload";

export default {
    name: "meeting-display-card",

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
