<template>
    <div class="card" style="width: 25rem;">

        <div class="card-header">
            <div class="h4 card-title">Candidate pool</div>
        </div>

        <ul class="list-group list-group-flush">

            <candidate-setup-row
                v-for="candidate in candidatePool"
                :candidate="candidate"
                :key="candidate.id"
                :is-pool="true"
            ></candidate-setup-row>

        </ul>

    </div>


</template>

<script>
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import CandidateSetupRow from "./candidate-setup-row";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "candidate-pool-card",
    components: {CandidateSetupRow},
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        candidatePool: {
            get: function () {
                let me = this;
                if (!isReadyToRock(this.motion)) return [];
                return this.$store.dispatch('loadCandidatePool', this.motion.id).then(function(){
                    return me.$store.getters.getCandidatePoolForOffice(me.motion);
                })

            },
            default: [],
            watch: ['motion']
        },


    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
