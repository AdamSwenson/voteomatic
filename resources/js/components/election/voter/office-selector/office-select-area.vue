<template>
    <div class="card office-select-area">

        <div class="card-body border-bottom py-3" aria-live="polite">
            <p class="text-uppercase small fw-bold mb-1">Your ballot progress</p>
            <p class="mb-2"><strong>{{ completedCount }} of {{ motions.length }}</strong> contests completed</p>
            <div class="progress" role="progressbar" :aria-valuenow="completedCount" aria-valuemin="0" :aria-valuemax="motions.length" aria-label="Ballot completion">
                <div class="progress-bar" :style="{width: completionPercentage + '%'}"></div>
            </div>
        </div>

        <div class="list-group list-group-flush">
            <instructions-row></instructions-row>

            <div v-if="showOffices">
                <office-select-row
                    :motion="m"
                    v-for="m in offices"
                    :key="m.id"
                ></office-select-row>
            </div>

            <div v-if="showPropositions">
                <proposition-select-row
                    :motion="p"
                    v-for="p in propositions"
                    :key="p.id"
                ></proposition-select-row>
            </div>

            <summary-select-row></summary-select-row>

        </div>
    </div>


</template>

<script>
import MotionMixin from "../../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../../mixins/meetingMixin";
import motionObjectMixin from "../../../../mixins/motionObjectMixin";
import OfficeSelectRow from "./office-select-row";
import SummarySubmitCard from "../summary-submit-card";
import SummarySelectRow from "./summary-select-row";
import InstructionsRow from "./instructions-row";
import PropositionSelectRow from "./proposition-select-row";

export default {
    name: "office-select-area",
    components: {PropositionSelectRow, InstructionsRow, SummarySelectRow, SummarySubmitCard, OfficeSelectRow},
    props: {
        showOffices: {
            default: true
        },
        showPropositions: {
            default: true
        }
    },
    mixins: [MotionMixin, MeetingMixin, motionObjectMixin],


    data: function () {
        return {}
    },


    computed: {
    // asyncComputed: {
        offices: function () {

            let m = this.$store.getters.getStoredMotions;
            if (_.isUndefined(m)) return [];

            m = _.filter(m, (o) => {
                return o.type !== 'proposition';
            });

            m = _.sortBy(m, ['id']);
            // m = _.reverse(m);

            return m;

        },

        propositions: function () {

            let m = this.$store.getters.getStoredMotions;
            if (_.isUndefined(m)) return [];

            m = _.filter(m, (o) => {
                return o.type === 'proposition';
            });

            m = _.sortBy(m, ['id']);
            // m = _.reverse(m);

            return m;

        },

        motions: function () {
            let m = this.$store.getters.getStoredMotions;
            if (_.isUndefined(m)) return [];


            m = _.sortBy(m, ['id']);
            // m = _.reverse(m);

            return m;
        },

        completedCount: function () {
            return this.motions.filter((motion) => this.$store.getters.hasVotedOnMotion(motion)).length;
        },

        completionPercentage: function () {
            if (this.motions.length === 0) return 0;
            return Math.round((this.completedCount / this.motions.length) * 100);
        }

    },


    methods: {
        // handleNext : function(){
        //     this.$store.dispatch('nextOfficeInStack');
        // },
        // handlePrevious: function(){
        //     this.$store.dispatch('previousOffice');
        // }
    }

}
</script>

<style scoped>

</style>
