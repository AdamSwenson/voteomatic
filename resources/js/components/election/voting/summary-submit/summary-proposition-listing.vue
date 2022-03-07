<template>
    <div class="summary-listing card-body">
        <h4>{{ propositionName }}</h4>
        <div class="ms-3" >
<!--        <div class="ms-3" v-if="! hasVoted">-->
            <ul>
                <li
                    v-bind:class="styling"
                >{{ response }}
                </li>
            </ul>

            <div class="abstention-message"
                 v-if="isAbstention">
                <p class="text-muted">You have abstained from voting on this issue. Abstentions are not 'No' votes.
                    Unlike 'No' votes, they do not
                    count in the denominator when determining whether a proposal passes. </p>
                <p class="text-muted">You will be able to return and vote on this issue after recording your vote.</p>
                <p class="text-muted">If this is not your intent, please go back and select 'Aye' or 'Nay' before
                    recording your selections.</p>
            </div>

        </div>

    </div>
</template>

<script>
import {isReadyToRock} from "../../../../utilities/readiness.utilities";

export default {
    name: "summary-proposition-listing",

    props: ['motion'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        /**
         * If there's no vote object, they either forgot to vote or are abstaining or
         * already voted
         *
         * @returns {boolean}
         */
        isAbstention: function () {
            return isReadyToRock(this.motion) && !isReadyToRock(this.voteObj) && ! this.hasVoted   },

        hasVoted: function(){
           return  isReadyToRock(this.motion) && this.$store.getters.hasVotedOnMotion(this.motion);
        },

        propositionName: function () {
            if (!isReadyToRock(this.motion)) return '';
            return this.motion.info.name;
        },

        voteObj: function () {
            if (isReadyToRock(this.motion)) {
                return this.$store.getters.getPropositionVoteForMotion(this.motion)
            }
        },

        response: function () {
            //Nothing if the motion hasn't loaded
            if (!isReadyToRock(this.motion) || !isReadyToRock(this.isAbstention)) return '';

            //Nothing if they have already voted
            if(this.hasVoted) return '';

            //If there's no vote object, they either forgot to vote or
            //are abstaining
            if (this.isAbstention) return 'Abstain';

            //English text
            return this.voteObj.voteDisplayYesNo;

        },

        styling: function () {
            if (!isReadyToRock(this.motion)) return '';

            // if (this.$store.getters.showOverSelectionWarningForMotion(this.motion)) return ' text-danger '
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
