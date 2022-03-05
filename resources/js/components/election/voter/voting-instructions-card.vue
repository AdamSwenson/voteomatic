<template>
    <div class="card voting-instructions-card">

        <div class="card-header" >
            <h1 class="card-title">Instructions</h1>
        </div>

        <div class="card-body" >
            <p class="card-text">We appreciate you taking the time to vote your representatives.</p>

            <p class="card-text">[todo video link]</p>

            <p class="card-text">Please make your selections for each office on the following screens. Your votes will not be recorded
                until you review and submit them on the final page. If you refresh your browser before recording your selections,
                the selections will be lost.</p>

            <p class="card-text">You may vote for some offices and return later to vote for others.
                However, since your vote is recorded anonymously, once you review and submit your choices for an office,
                it cannot be altered. If you vote for less than the maximum number of candidates for an office, you will <strong>not</strong> be able to vote for more after you submit your choices.</p>

            <p class="card-text">For more information about how the voteomatic keeps your vote
                anonymous, please see <a href="https://github.com/AdamSwenson/voteomatic#anonymity">https://github.com/AdamSwenson/voteomatic#anonymity</a> </p>


        </div>

        <div class="card-body">
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
