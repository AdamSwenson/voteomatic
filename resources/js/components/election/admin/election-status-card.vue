<template>
    <div class="election-status-card card col">

        <div class="card-body">
            <div class="row">
                <dl class="col-md-6">
                    <dt>Voting open</dt>
                    <dd>{{ isVotingAvailable }}</dd>
                </dl>
                <dl class="col-md-6">
                    <dt>Results available</dt>
                    <dd>{{ isResultsAvailable }}</dd>
                </dl>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <dl class="col-md-6">
                    <dt>Total voters participating</dt>
                    <dd>{{ totalVotesCast }}</dd>

                    <!--            <dl class="col-md-6">-->
                    <!--                <dt></dt>-->
                    <!--                <dd>{{ isResultsAvailable }}%</dd>-->
                </dl>
            </div>
        </div>


    </div>

</template>

<script>

import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MeetingMixin from "../../../mixins/meetingMixin";

export default {
    name: "election-status-card",

    props: [],


    mixins: [MeetingMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        isVotingAvailable: function () {
            if (!isReadyToRock(this.meeting)) return '-';
            return this.meeting.isVotingAvailable ? 'Yes' : 'No';
        },
        isResultsAvailable: function () {

            if (!isReadyToRock(this.meeting)) return '-';
            return this.meeting.isResultsAvailable ? 'Yes' : 'No';
        },

        totalVotesCast: function () {

            if (!isReadyToRock(this.meeting)) return '-';
            return this.$store.getters.getCastVotesCount;
            // return '-';
        },

        officeVotesCast: function () {
        },

        voterCount: function(){
            if (!isReadyToRock(this.meeting)) return '-';
            return this.$store.getters.getMemberCount;
        }

    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
