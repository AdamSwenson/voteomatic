<template>
    <div class="card voting-instructions-card">

        <div class="card-header">
            <h1 class="card-title">Instructions</h1>
        </div>

        <div class="card-body">
            <p class="card-text">We appreciate you taking the time to vote for your representatives.</p>

            <!--            <p class="card-text">[todo video link]</p>-->
            <p class="card-text">Please click 'Start voting' below or on an office in the list to the left (above, if
                you're on a
                small device) to make your selection(s) for each position.</p>

            <p class="card-text">Your selections will not be recorded
                until you review and submit them on the final page. If you refresh your browser before recording them,
                the selections will be lost.</p>

            <p class="card-text">To maintain anonymity, there is no way to change your vote after you submit your ballot. </p>

<!--            <p class="card-text">You may vote for some positions and return later to vote for others.-->
<!--                However, since your vote is recorded anonymously, once you review and submit your choice for a-->
<!--                position,-->
<!--                it cannot be altered. If you vote for less than the maximum number of candidates for an position, you-->
<!--                will <strong>not</strong> be able to vote for more after you submit your choices.</p>-->
        </div>
        <div class="card-body">
            <p class="card-text">For information about the receipts you will receive after voting,
                please see <a href="https://github.com/AdamSwenson/voteomatic#receipts" target="_blank"
                              rel="noopener noreferrer">https://github.com/AdamSwenson/voteomatic#receipts</a></p>

            <p class="card-text">For information about how the voteomatic keeps your vote
                anonymous, please see <a href="https://github.com/AdamSwenson/voteomatic#anonymity" target="_blank"
                                         rel="noopener noreferrer">https://github.com/AdamSwenson/voteomatic#anonymity</a>
            </p>

            <p class="card-text">We will greatly appreciate reports of any annoyances, bugs, unintuitive steps, or
                other issues you encounter:
                <a href="https://github.com/AdamSwenson/voteomatic/issues" target="_blank"
                   rel="noopener noreferrer">https://github.com/AdamSwenson/voteomatic/issues</a></p>

        </div>


        <div class="card-body" v-if="! isElectionComplete">
            <div class="d-grid gap-2">
                <button class="btn btn-success" v-on:click="handleStart">Start voting</button>
            </div>
        </div>

    </div>
</template>

<script>
import MotionMixin from "../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../mixins/meetingMixin";
import motionObjectMixin from "../../../mixins/motionObjectMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "voting-instructions-card",

    props: [],
    mixins: [MotionMixin, MeetingMixin, motionObjectMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        electionName: function () {
            if (!isReadyToRock(this.meeting)) return ''
            return this.meeting.name;
        },
        /**
         * True if the voter has voted on all offices
         */
        isElectionComplete: function () {
            return this.$store.getters.isElectionComplete;
        }
    },

    computed: {},

    methods: {
        handleStart: function () {
            this.$store.dispatch('nextOffice');

        }
    }

}
</script>

<style scoped>

</style>
