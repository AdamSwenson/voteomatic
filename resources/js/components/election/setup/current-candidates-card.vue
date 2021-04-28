<template>

    <div class="card" style="width: 25rem;">
        <div class="card-header">
            <h4 class="card-title">Candidates</h4></div>

            <ul class="list-group list-group-flush">
                <candidate-setup-row
                    v-for="candidate in candidates"
                    :candidate="candidate"
                    :key="candidate.id"
                    :is-pool="false"
                    v-on:selection="handleSelection"
                ></candidate-setup-row>
            </ul>

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
        return {
            events : 0
        }
    },

    asyncComputed: {
        candidates: {
            get: function () {
                if (!isReadyToRock(this.motion)) return [];
                return this.$store.getters.getCandidatesForOffice(this.motion);
            },
            default: [],
            watch: ['motion', 'events']
        },

    },

    computed: {},

    methods: {
        handleSelection : function(){
            //this increments a dummy variable so that the
            //async computed property will know to change
            this.events += 1;
            window.console.log('selection-handler', this.events);
        }
    }

}
</script>

<style scoped>

</style>
