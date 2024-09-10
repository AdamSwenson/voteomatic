<template>
    <div class="card">

        <div class="card-header">
            <div class="h5 card-title">Eligible for nomination</div>
        </div>

        <div class="list-group list-group-flush">

            <candidate-setup-row
                v-for="candidate in candidatePool"
                :candidate="candidate"
                :key="candidate.id"
                :is-pool="true"
            ></candidate-setup-row>

        </div>

        <pool-member-creation-card></pool-member-creation-card>

        <div class="card-footer">
            <div class="row ">

                <div class="col-md-auto">
                    <create-pool-member-button></create-pool-member-button>
                </div>

                <div class="col-md-auto">
                    <import-pool-controls></import-pool-controls>
                </div>

            </div>

        </div>

    </div>


</template>

<script>
import MeetingMixin from "../../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../../mixins/motionStoreMixin";
import CandidateSetupRow from "../candidates/candidate-setup-row";
import {isReadyToRock} from "../../../../utilities/readiness.utilities";
import PoolMemberCreationCard from "./create/pool-member-creation-card";
import ImportPoolControls from "./import/import-pool-controls";
import CreatePoolMemberButton from "./create/create-pool-member-button";

export default {
    name: "candidate-pool-card",
    components: {CreatePoolMemberButton, ImportPoolControls, PoolMemberCreationCard, CandidateSetupRow},
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],

    data: function () {
        return {

        }
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
                    return _.sortBy(p, function(c){return c.last_name;});
                }
            },
            default: [],
            watch: ['motion']
        },


    },

    computed: {},

    methods: {

    },

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
