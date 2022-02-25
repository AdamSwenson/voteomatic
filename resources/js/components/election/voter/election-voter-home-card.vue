<template>
    <div class="election-home-card card">
<!--        <div class="card-header">-->
<!--            <h3 class="card-header">{{electionName}}</h3>-->
<!--        </div>-->

        <div class="card-body">
            <div class="row">
                <div class="col-md-3" v-if="! isVotingComplete">
                    <office-select-area></office-select-area>
                </div>
                <div class="col-md-9">
                    <component v-bind:is="shownCard"></component>
<!--                    -->
<!--                    <voting-complete-card v-if="isVotingComplete"></voting-complete-card>-->
<!--                    <summary-submit-card v-else-if="showSummarySubmitCard"></summary-submit-card>-->
<!--                    <election-card v-else-if="isOfficeSelected"></election-card>-->
<!--                    <voting-instructions-card v-else></voting-instructions-card>-->

                    </div>
            </div>
        </div>

        <!--    <div class="card-body">-->

        <!--        <p class="card-text">All voting happens here. If voting is complete, displays a thank you</p>-->

        <!--    </div>&lt;!&ndash;&ndash;&gt;-->

    </div>
</template>

<script>


import OfficeSelectArea from "./office-selector/office-select-area";
import VotingCompleteCard from "../unavailable/voting-complete-card";
import MotionMixin from "../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../mixins/meetingMixin";
import motionObjectMixin from "../../../mixins/motionObjectMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import VotingInstructionsCard from "./voting-instructions-card";
import SummarySubmitCard from "./summary-submit-card";
import ElectionCard from "../voting/election-card";

/**
 * This will hold the new voter interface
 */
export default {
    name: "election-voter-home-card",
    components: {SummarySubmitCard, VotingInstructionsCard, VotingCompleteCard, OfficeSelectArea},
    props: [],
    mixins: [MotionMixin, MeetingMixin, motionObjectMixin],


    data: function () {
        return {
            // showableCards : {
            //     //Allows user to select candidates
            //     'election' : ElectionCard,
            //     //Tells the user they are not allowed to vote
            //     'complete' : VotingCompleteCard,
            //     //Tells the user how to vote
            //     'instructions' : VotingInstructionsCard,
            //     //User submits their selections
            //     'summary' : SummarySubmitCard,
            //
            // }
        }
    },

    asyncComputed: {
        electionName: function () {
            if (!isReadyToRock(this.meeting)) return ''
            return this.meeting.name;
        },

        /**
         * Handles all the logic for determining
         * what card gets shown
         */
        shownCard: function(){
            return this.$store.getters.getShownCard;
        },

        // showableCards : function (){
        //     return this.$state.getShowableCards;
        // },

        /**
         * Returns true if the election has closed or if the
         * user has voted
         */
        isVotingComplete: function(){
                return this.$store.getters.isCompleteCardShown || this.$store.getters.isElectionComplete;

        },

        /**
         * Whether there is currently a motion selected to be
         * voted upon
         */
        isOfficeSelected: function(){
            return isReadyToRock(this.motion);
        },

        showSummarySubmitCard: function(){
            return this.$store.getters.isSummarySubmitCardVisible;
        }
    },


    computed: {},

    methods: {},

    mounted() {
        // this.$store.dispatch('loadAllOfficeCandidates');
        // this.$store.commit('showInstructionsCard');
    }

}
</script>

<style scoped>

</style>
