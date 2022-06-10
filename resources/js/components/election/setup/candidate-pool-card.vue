<template>
    <div class="card" style="width: 25rem;">

        <div class="card-header">
            <div class="h4 card-title">Eligible for nomination</div>
        </div>

        <ul class="list-group list-group-flush">

            <candidate-setup-row
                v-for="candidate in candidatePool"
                :candidate="candidate"
                :key="candidate.id"
                :is-pool="true"
            ></candidate-setup-row>

        </ul>

        <pool-member-creation-card></pool-member-creation-card>


    </div>


</template>

<script>
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import CandidateSetupRow from "./candidate-setup-row";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import PoolMemberCreationCard from "./pool-member-creation-card";
import ImportPoolControls from "./import-pool/import-pool-controls";

export default {
    name: "candidate-pool-card",
    components: {ImportPoolControls, PoolMemberCreationCard, CandidateSetupRow},
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],

    data: function () {
        return {}
    },

    watch: {
        /**
         * This handles loading the pool on subsequent changes of the
         * motion.
         */
        motion: function () {
            this.$store.dispatch('loadCandidatePool', this.motion.id);
        }

    },

    asyncComputed: {
        candidatePool: {
            get: function () {
                let me = this;
                if (!isReadyToRock(this.motion)) return [];

                let p = me.$store.getters.getCandidatePoolForOffice(me.motion);
                if (p.length > 0) {
                    return p;
                }
            },
            default: [],
            watch: ['motion']
        },


    },

    computed: {},

    methods: {},

    mounted() {
        //This ensures that the pool loads for the first time the edit
        //button is clicked.
        if (isReadyToRock(this.motion)) {
            this.$store.dispatch('loadCandidatePool', this.motion.id).then(function () {
            });
        }
        }

}
</script>

<style scoped>

</style>
