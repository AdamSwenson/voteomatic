<template>

    <div class="card" >
        <div class="card-header">
            <h5 class="card-title">Candidates</h5></div>

        <div class="list-group list-group-flush">

<!--    <ul class="list-group list-group-flush">-->
                <candidate-setup-row
                    v-for="candidate in candidates"
                    :candidate="candidate"
                    :key="candidate.id"
                    :is-pool="false"
                    v-on:selection="handleSelection"
                ></candidate-setup-row>
<!--            </ul>-->
</div>
    </div>

</template>

<script>
import CandidateSetupRow from "./candidate-setup-row";
import MeetingMixin from "../../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../../mixins/motionStoreMixin";
import {isReadyToRock} from "../../../../utilities/readiness.utilities";

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

    watch: {
    /**
     * This handles loading the candidates on subsequent changes of the
     * motion.
     */
    motion: function () {
        this.$store.dispatch('loadElectionCandidates', this.motion.id);
    }
},

    asyncComputed: {
        candidates: {
            get: function () {
                if (!isReadyToRock(this.motion)) return [];
                let p = this.$store.getters.getCandidatesForOffice(this.motion);
                if (p.length > 0) {
                    return _.sortBy(p, function(c){return c.last_name;});
                }
                // return this.$store.getters.getCandidatesForOffice(this.motion);
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
    },

    mounted() {
        //This ensures that the list of candidates loads for the first time the edit
        //button is clicked.
        if (isReadyToRock(this.motion)) {
            this.$store.dispatch('loadElectionCandidates', this.motion.id).then(function () {
            });
        }
    }

}
</script>

<style scoped>

</style>
