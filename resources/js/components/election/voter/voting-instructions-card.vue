<template>
    <div class="card voting-instructions-card">

        <div class="card-header">Instructions</div>

        <div class="card-body" v-if="! isVotingAllowed">
            <p class="card-text">Voting is not currently allowed in this election</p>
        </div>

        <div class="card-body" v-else>
            <p class="card-text">Instructions here. </p>

            <p class="card-text">Click an office to start</p>
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

    methods: {}

}
</script>

<style scoped>

</style>
