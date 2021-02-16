<template>
    <div class="office-results-card card">

        <div class="card-header">
            <h2 class="card-title">Results for the election to {{ officeName }}</h2>
        </div>

        <div class="card-body">
            <p>Total votes cast for office: {{ totalCast }}</p>

        </div>

        <div class="card-body">

            <candidate-result-row v-for="result in results"
                                  :key="result.candidateId"
                                  :result="result"
            ></candidate-result-row>
        </div>

    </div>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import * as routes from "../../../routes";
import CandidateResultRow from "./candidate-result-row";

export default {
    name: "office-results-card",
    components: {CandidateResultRow},
    props: ['motion'],

    mixins: [MeetingMixin],


    data: function () {
        return {}
    },

    asyncComputed: {

        results: function () {
            if (isReadyToRock(this.motion)) {
                let r = this.$store.getters.getOfficeResults(this.motion);

                //filter out nameless write ins.
                //todo deal with this on the server
                return r.filter((result) => {
                    return !_.isNull(result.candidateName) && ! _.isNull(result.voteCount);
                });
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

        office: {
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

        officeName: {
            get: function () {
                if (isReadyToRock(this.motion)) return this.motion.content;
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
