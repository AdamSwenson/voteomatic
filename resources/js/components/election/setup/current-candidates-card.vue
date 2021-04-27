<template>

    <div class="card" style="width: 18rem;">
        <div class="card-header">
            <h4 class="card-title">Candidates</h4></div>

<!--        <div class="card-body" >-->
            <ul class="list-group list-group-flush">
                <candidate-setup-row v-for="candidate in candidates" :candidate="candidate" :key="candidate.id"
                                     :is-pool="false"></candidate-setup-row>
            </ul>
            <!--                    class="list-group-item"-->
            <!--                ><button class="btn btn-warning" v-on:click="handleClick"-->
            <!--                    >Remove</button>  {{ candidate.name }}</li>-->

            <!--            </ul>-->

<!--        </div>-->

    </div>

</template>

<script>
import CandidateSetupRow from "./candidate-setup-row";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "current-candidates-card",
    components: {CandidateSetupRow},
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        candidates: {
            get: function () {
                if (!isReadyToRock(this.motion)) return [];
                return this.$store.getters.getCandidatesForOffice(this.motion);
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
