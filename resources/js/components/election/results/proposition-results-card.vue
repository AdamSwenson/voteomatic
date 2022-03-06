<template>
    <div class="proposition-results-card card">

        <div class="card-header">
            <h2 class="card-title">{{ propositionName }} results</h2>
        </div>

<!--        <div class="card-body">-->
<!--            <p>Total votes cast for proposition: {{ totalCast }}</p>-->

<!--        </div>-->

        <div class="card-body">
            <proposition-result-row
                v-for="result in results"
                                  :key="result.candidateId"
                                  :result="result"
            ></proposition-result-row>
        </div>

    </div>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import * as routes from "../../../routes";
import CandidateResultRow from "./candidate-result-row";
import PropositionResultRow from "./proposition-result-row";

export default {
    name: "proposition-results-card",
    components: {PropositionResultRow},
    props: ['motion'],

    mixins: [MeetingMixin],


    data: function () {
        return {}
    },

    asyncComputed: {

        results: function () {
            if (isReadyToRock(this.motion)) {
                let r = this.$store.getters.getOfficeResults(this.motion);
return r;
                // //filter out nameless write ins.
                // //todo deal with this on the server
                // return r.filter((result) => {
                //     return !_.isNull(result.candidateName) && ! _.isNull(result.voteCount);
                // });
            }


            // let url = routes.election.getResults(motionId);
            //
            // return new Promise(((resolve, reject) => {
            //     return Vue.axios.get(url).then((response) => {
            //         return resolve(response.data);
            //     });
            // }));
        },

        totalCast: function () {
            if (isReadyToRock(this.results)) {
                //todo (can't just be the vote count where multiple winners)
            }
        },

        // counts: function () {
        //     if(isReadyToRock(this.motion) && isReadyToRock(this.results)){
        //         return this.results.counts;
        //         // return this.$store.getters.getVoteCounts(this.motion.id);
        //     }
        //
        //     return [];
        // },
        //
        // shares: function () {
        //     if(isReadyToRock(this.motion)){
        //         return this.results.shares;
        //         // return this.$store.getters.getVoteCounts(this.motion.id);
        //     }
        //
        //     // let results = this.results;
        //     // if (isReadyToRock(results)) return results.counts;
        //
        // },

        proposition: {
            get: function () {
                return this.motion;
            },
            default: null

        },

        maxWinners: {
            get: function () {
                if (isReadyToRock(this.motion)) return this.motion.max_winners;

                // return ''
            },

            default: null

            // },
        },

        propositionName: {
            get: function () {
                if (isReadyToRock(this.motion)) return this.motion.info.name;
            },
            default: ''
        }
    },

    computed: {},

    methods: {},

    mounted() {
        this.$store.dispatch('loadResultsForOffice', this.motion.id);
    }

}
</script>

<style scoped>

</style>
