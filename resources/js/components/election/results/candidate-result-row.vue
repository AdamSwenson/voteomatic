<template>
    <div class="candidate-result-row card-body" v-bind:class="styling">
        <p class="h4">{{ candidateName }}   <component v-bind:is="badgeShown"></component>
        </p>
        <!--        <dl class="row">-->
        <div class="row" v-if="showVoteTotal || showVoteShare">
            <dl class="col-md-6" v-if="showVoteTotal">
                <dt>Votes received</dt>
                <dd>{{ totalVote }}</dd>
            </dl>
            <dl class="col-md-6" v-if="showVoteShare">
                <dt>Share of votes cast</dt>
                <dd>{{ voteShare }}%</dd>
                <!--            <dt class="col-sm-6">Votes received</dt>-->
                <!--            <dd class="col-sm-6">{{ totalVote }}</dd>-->

                <!--            <dt class="col-sm-6">Share of votes cast</dt>-->
                <!--            <dd class="col-sm-6">{{ voteShare }}%</dd>-->
            </dl>
        </div>
    </div>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import WinnerBadge from "./winner-badge";
import RunoffBadge from "./runoff-badge";

export default {
    name: "candidate-result-row",
    components: {RunoffBadge, WinnerBadge},
    props: ['result'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        badgeShown: function () {
            if (this.isWinner) return WinnerBadge;
            if (this.isRunoff) return RunoffBadge;
        },

        isWinner: function () {
            if (isReadyToRock(this.result)) return this.result.isWinner;
        },

        isRunoff: function () {
            if (isReadyToRock(this.result)) return this.result.isRunoffParticipant;
        },

        candidateName: function () {
            if (isReadyToRock(this.result)) return this.result.candidateName;
        },

        totalVote: function () {
            if (isReadyToRock(this.result)) return this.result.voteCount;
        },

        /**
         * Whether to display the total number of
         * votes the candidate received
         */
        showVoteTotal : function(){
            return true;
        },

        /**
         * Whether to display the percentage the candidate received
         * @returns {boolean}
         */
        showVoteShare : function (){
            return true;
        },

        styling: function () {
            if (isReadyToRock(this.result)) {

                // if (this.result.isWinner) return 'winner'//'text-success'; //'bg-success';

                // if (this.result.isRunoffParticipant) return 'bg-warning';

                // switch (this.result) {
                //
                //     case this.result.isWinner:
                //         return 'bg-success';
                //         break;
                //
                //     case this.result.isRunoffParticipant:
                //         return 'bg-warning';
                //         break;
                //
                //     default:
                //         return ''
                // }
            }
        },

        voteShare: function () {
            if (isReadyToRock(this.result)) {
                return this.result.voteShareAsPercentage;
            }
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>
.winner {
    color: #396f3a;
}
</style>
