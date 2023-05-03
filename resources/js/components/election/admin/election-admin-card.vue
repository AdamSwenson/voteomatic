<template>
    <div class="election-admin-card card router-tab-touching-card">

<!--        adding g-4 makes overflow vertically-->
            <div class="row row-cols-1 row-cols-md-2 ">
                <!--        <div class="row ">-->

            <div class="election-controls card col">

                <div class="card-body">
                    <h4 class="card-title">Election phase</h4>
                    <election-phase-selector :meeting="meeting"></election-phase-selector>
                </div>

                <div class="card-body">
                    <release-results-button></release-results-button>
                </div>

                <div class="card-body">

                    <dl class="row">
                        <dt class="col-sm-3">Setup</dt>
                        <dd class="col-sm-9">
                            <ul class="list-unstyled">
                                <li>Chair may create and populate offices</li>
                            </ul>

                        </dd>

                        <dt class="col-sm-3">Nominations</dt>
                        <dd class="col-sm-9">
                            <ul class="list-unstyled">
                                <li>Designated people may nominate candidates for offices</li>
                            </ul>
                        </dd>


                        <dt class="col-sm-3">Voting</dt>
                        <dd class="col-sm-9">
                            <ul class="list-unstyled">
                                <li>Users may vote</li>
                            </ul>
                        </dd>

                        <dt class="col-sm-3">Closed</dt>
                        <dd class="col-sm-9">
                            <ul class="list-unstyled">
                                <li>Voting disabled.</li>
                                <li>Chair may view results</li>
                                <li>All others see 'voting closed' message
                                </li>
                            </ul>
                        </dd>

                        <dt class="col-sm-3">Results</dt>
                        <dd class="col-sm-9">
                            <ul class="list-unstyled">
                                <li>All users may view results</li>
                            </ul>
                        </dd>
                    </dl>
                </div>

                <!--                <div class="card-body">-->
                <!--                    <start-election-button></start-election-button>-->
                <!--                    <end-election-button></end-election-button>-->
                <!--                </div>-->
                <!--                -->
                <!--                <div class="card-body">-->
                <!--                    <release-results-button></release-results-button>-->
                <!--                    <hide-results-button></hide-results-button>-->
                <!--                </div>-->
                <!--               -->

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
import ElectionPhaseSelector from "./election-phase-selector";

export default {
    name: "election-admin-card",
    components: {
        ElectionPhaseSelector,
        ElectionStatusCard, HideResultsButton, StartElectionButton, ReleaseResultsButton, EndElectionButton
    },
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
        },


    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
