å
<template>
    <div class="proposition-result-row" v-bind:class="styling">
        <h4>{{ propositionName }}
            <proposition-approved-badge v-if="isApproved"></proposition-approved-badge>
        </h4>

        <div class="row" v-if="showVoteTotal || showVoteShare">
            <dl class="col-md-6" v-if="showVoteTotal">
                <dt>Votes received</dt>
                <dd>{{ totalVote }}</dd>
            </dl>
            <dl class="col-md-6" v-if="showVoteShare">
                <dt>% in favor</dt>
                <dd class="col-sm-9">{{ voteShare }}%</dd>
            </dl>
        </div>

    </div>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import PropositionApprovedBadge from "./proposition-approved-badge";

export default {
    name: "proposition-result-row",
    components: {PropositionApprovedBadge},
    props: ['result'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {

        isApproved: function () {
            if (isReadyToRock(this.result)) return this.result.isWinner;
        },

        propositionName: function () {
            if (isReadyToRock(this.result)) return this.result.propositionName;
        },

        totalVote: function () {
            if (isReadyToRock(this.result)) return this.result.voteCount;
        },

        /**
         * Whether to display the total number of
         * votes the candidate received
         */
        showVoteTotal: function () {
            return true;
        },

        /**
         * Whether to display the percentage the candidate received
         * @returns {boolean}
         */
        showVoteShare: function () {
            return true;
        },


        styling: function () {
            if (isReadyToRock(this.result)) {

                // if(this.result.isWinner) return 'bg-success';

                // if(this.result.isRunoffParticipant) return 'bg-warning';
                //
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

</style>
