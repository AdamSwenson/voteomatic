<template>
    <div class="election-results-card card">
        <!--        <div class="card-header">-->
        <!--            <h1 class="card-title">Results </h1>-->
        <!--        </div>-->

        <div class="results-area" v-if="isVotingComplete">

            <div class="card-body">
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <!--            <office-results-card :motion="motion"></office-results-card>-->
                    <office-results-card v-for="motion in offices" :key="motion.id"
                                         :motion="motion"></office-results-card>
                </div>
            </div>

            <div class="card-body">
                <!--            <div class="row row-cols-1 row-cols-md-2 g-4">-->
                <!--            <div class="card-group">-->
                <proposition-results-card v-for="m in propositions" :key="m.id" :motion="m"></proposition-results-card>

            </div>
        </div>

        <div class="card-body" v-else>
            <p class="card-text">Results will be available once the Chair has closed voting</p>
            <p class="card-text">If you should be seeing the results, please try refreshing your browser</p>
        </div>

    </div>
</template>

<script>
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import OfficeResultsCard from "./office-results-card";
import ModeMixin from "../../../mixins/modeMixin";
import ChairMixin from "../../../mixins/chairMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import PropositionResultsCard from "./proposition-results-card";

export default {
    name: "election-results-card",
    components: {PropositionResultsCard, OfficeResultsCard},
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin,],

    data: function () {
        return {}
    },

    asyncComputed: {

        offices: function () {
            if (this.motions.length === 0) return []
            return _.filter(this.motions, (m) => {
                return m.type === 'election';
            });
        },

        propositions: function () {
            if (this.motions.length === 0) return []
            return _.filter(this.motions, (m) => {
                return m.type === 'proposition';
            });
        },

        motions: {
            get: function () {
                return this.$store.getters.getMotions;
            },
            default: []
        },
        isVotingComplete: function () {
            if (!isReadyToRock(this.meeting)) return false;
            return this.meeting.isComplete && !this.meeting.isVotingAvailable;
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
