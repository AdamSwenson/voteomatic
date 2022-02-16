<template>
    <div class="card voting-instructions-card">

        <div class="card-header">Instructions</div>

        <div class="card-body" >
            <p class="card-text"> TODO Instructions here. </p>

            <p class="card-text">If you refresh your browser before recording your selections,
                the selections will be lost. </p>

            <p class="card-text">You may record some selections and return later to record
                others. However, the past votes cannot be altered.</p>



            <p class="card-text">
                <button class="btn btn-lg btn-success" v-on:click="handleStart">Start voting</button>
            </p>
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
