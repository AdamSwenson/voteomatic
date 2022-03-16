<template>
    <div class="proposition-results-card card ">

        <div class="card-header">
            <h2 class="card-title">{{ propositionName }} </h2>
        </div>

        <div class="proposition-result-row card-body" v-bind:class="styling">
            <h4>
                <proposition-approved-badge v-if="isApproved"></proposition-approved-badge>
                <proposition-defeated-badge v-else></proposition-defeated-badge>
            </h4>
        </div>

        <div class="card-body">
            <div class="contentArea" v-html="motionContent"></div>
        </div>

        <div class="card-body">

            <!--            <div class="row">-->
            <!--                <div class="col-md-1">-->
            <!--                   </required-vote-badge>-->
            <!--                </div>-->
            <!--                <div class="col-md-3 ">-->
            <required-vote :motion="motion"></required-vote>
            <!--            <required-vote-badge :motion="motion"></required-vote-badge>-->
            <!--                </div>-->
            <!--                <div class="col-md-3"></div>-->
            <!--                <div class="col-md-3"></div>-->


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

        <!--            <proposition-result-row :motion="motion"-->
        <!--                v-for="result in results"-->
        <!--                                  :key="result.candidateId"-->
        <!--                                  :result="result"-->
        <!--            ></proposition-result-row>-->

    </div>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import * as routes from "../../../routes";
import CandidateResultRow from "./candidate-result-row";
import PropositionResultRow from "./proposition-result-row";
import RequiredVoteBadge from "../../motions/badges/required-vote-badge";
import RequiredVote from "../../text-display/required-vote";
import PropositionDefeatedBadge from "./proposition-defeated-badge";
import PropositionApprovedBadge from "./proposition-approved-badge";

/**
 * The voting results for 1 proposition
 */
export default {
    name: "proposition-results-card",
    components: {
        PropositionApprovedBadge,
        PropositionDefeatedBadge, RequiredVote, RequiredVoteBadge, PropositionResultRow
    },
    props: ['motion'],

    mixins: [MeetingMixin],


    data: function () {
        return {}
    },

    asyncComputed: {

        results: function () {
            if (isReadyToRock(this.motion)) {
                let r = this.$store.getters.getOfficeResults(this.motion);
                return r;
            }

        },

        /**
         * Alias because I can't make up my damn mind
         */
        result: function () {
            if (isReadyToRock(this.results)) return this.results[0];
        },


        isApproved: function () {
            if (isReadyToRock(this.result)) return this.result.isWinner;
        },

        // propositionName: function () {
        //     if (isReadyToRock(this.result)) return this.result.propositionName;
        // },

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
        },

        totalCast: function () {
            if (isReadyToRock(this.results)) {
                //todo (can't just be the vote count where multiple winners)
            }
        },
        motionContent: function () {
            if (!isReadyToRock(this.motion)) return ''
            return this.motion.content;
        },


        proposition: {
            get: function () {
                return this.motion;
            },
            default: null

        },

        maxWinners: {
            get: function () {
                if (isReadyToRock(this.motion)) return this.motion.max_winners;

                // return ''
            },

            default: null

            // },
        },

        propositionName: {
            get: function () {
                if (isReadyToRock(this.motion)) return this.motion.info.name;
            },
            default: ''
        }
    },

    computed: {},

    methods: {},

    mounted() {
        this.$store.dispatch('loadResultsForOffice', this.motion.id);
    }

}
</script>

<style scoped>

</style>
