
<!--<template>-->
<!--    <div class="proposition-result-row card-body" v-bind:class="styling">-->
<!--        <h4><proposition-approved-badge v-if="isApproved"></proposition-approved-badge>-->
<!--        <proposition-defeated-badge v-else></proposition-defeated-badge>-->
<!--        </h4>-->

<!--        <p class="card-text">-->
<!--        <required-vote :motion="motion"></required-vote>-->
<!--        <required-vote-badge :motion="motion"></required-vote-badge>-->
<!--        </p>-->

<!--        <div class="row" v-if="showVoteTotal || showVoteShare">-->
<!--            <dl class="col-md-6" v-if="showVoteTotal">-->
<!--                <dt>Votes received</dt>-->
<!--                <dd>{{ totalVote }}</dd>-->
<!--            </dl>-->
<!--            <dl class="col-md-6" v-if="showVoteShare">-->
<!--                <dt>% in favor</dt>-->
<!--                <dd class="col-sm-9">{{ voteShare }}%</dd>-->
<!--            </dl>-->
<!--        </div>-->

<!--    </div>-->
<!--</template>-->

<!--<script>-->
<!--import {isReadyToRock} from "../../../utilities/readiness.utilities";-->
<!--import PropositionApprovedBadge from "./proposition-approved-badge";-->
<!--import PropositionDefeatedBadge from "./proposition-defeated-badge";-->
<!--import RequiredVoteBadge from "../../motions/badges/required-vote-badge";-->
<!--import RequiredVote from "../../text-display/required-vote";-->

<!--export default {-->
<!--    name: "proposition-result-row",-->
<!--    components: {RequiredVote, RequiredVoteBadge, PropositionDefeatedBadge, PropositionApprovedBadge},-->
<!--    props: ['result', 'motion'],-->

<!--    mixins: [],-->

<!--    data: function () {-->
<!--        return {}-->
<!--    },-->

<!--    asyncComputed: {-->

<!--        isApproved: function () {-->
<!--            if (isReadyToRock(this.result)) return this.result.isWinner;-->
<!--        },-->

<!--        propositionName: function () {-->
<!--            if (isReadyToRock(this.result)) return this.result.propositionName;-->
<!--        },-->

<!--        totalVote: function () {-->
<!--            if (isReadyToRock(this.result)) return this.result.voteCount;-->
<!--        },-->

<!--        /**-->
<!--         * Whether to display the total number of-->
<!--         * votes the candidate received-->
<!--         */-->
<!--        showVoteTotal: function () {-->
<!--            return true;-->
<!--        },-->

<!--        /**-->
<!--         * Whether to display the percentage the candidate received-->
<!--         * @returns {boolean}-->
<!--         */-->
<!--        showVoteShare: function () {-->
<!--            return true;-->
<!--        },-->


<!--        styling: function () {-->
<!--            if (isReadyToRock(this.result)) {-->

<!--                // if(this.result.isWinner) return 'bg-success';-->

<!--                // if(this.result.isRunoffParticipant) return 'bg-warning';-->
<!--                //-->
<!--                // switch (this.result) {-->
<!--                //-->
<!--                //     case this.result.isWinner:-->
<!--                //         return 'bg-success';-->
<!--                //         break;-->
<!--                //-->
<!--                //     case this.result.isRunoffParticipant:-->
<!--                //         return 'bg-warning';-->
<!--                //         break;-->
<!--                //-->
<!--                //     default:-->
<!--                //         return ''-->
<!--                // }-->
<!--            }-->
<!--        },-->

<!--        voteShare: function () {-->
<!--            if (isReadyToRock(this.result)) {-->
<!--                return this.result.voteShareAsPercentage;-->
<!--            }-->
<!--        }-->
<!--    },-->

<!--    computed: {},-->

<!--    methods: {}-->

<!--}-->
<!--</script>-->

<!--<style scoped>-->

<!--</style>-->
