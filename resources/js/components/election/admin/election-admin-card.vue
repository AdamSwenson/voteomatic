<template>
    <div class="election-admin-card card">
        <div class="row row-cols-1 row-cols-md-2 g-4">
        <div class="card election-controls col">

        <div class="card-body">
            <start-election-button></start-election-button>
            <end-election-button></end-election-button>
            </div>
        <div class="card-body">
            <release-results-button ></release-results-button>
            <hide-results-button ></hide-results-button>
        </div>
<!--            <release-results-button v-if="showReleaseButton"></release-results-button>-->
<!--            <hide-results-button v-if="showHideButton"></hide-results-button>-->
        </div>

        <election-status-card></election-status-card>


</div>
    </div>
</template>

<script>
import EndElectionButton from "./end-election-button";
import ReleaseResultsButton from "./release-results-button";
import StartElectionButton from "./start-election-button";
import AllReceiptsMixin from "../../../mixins/allReceiptsMixin";
import MeetingMixin from "../../../mixins/meetingMixin";
import HideResultsButton from "./hide-results-button";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import ElectionStatusCard from "./election-status-card";

export default {
    name: "election-admin-card",
    components: {ElectionStatusCard, HideResultsButton, StartElectionButton, ReleaseResultsButton, EndElectionButton},
    props: [],


    mixins: [MeetingMixin],
    data: function () {
        return {}
    },

    asyncComputed: {
        showReleaseButton: function () {
            if (!isReadyToRock(this.meeting)) return false;
            return this.meeting.isComplete && !this.isVotingAvailable && !this.isResultsAvailable;
        },
        showHideButton: function () {
            if (!isReadyToRock(this.meeting)) return false;
            return this.meeting.isComplete && !this.isVotingAvailable && this.isResultsAvailable;
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
