<template>
    <div class="election-card">

        <div class="card" v-if="isElection">

            <div class="card-header">
                <h2 class="card-title">{{ officeName }}</h2>
            </div>

            <max-winners-instruction></max-winners-instruction>
            <overselection-warning></overselection-warning>

            <!--        <div class="card-body instructions" v-if="instructions.length > 0">-->
            <!--            {{ instructions }}-->
            <!--        </div>-->

            <div class="card-body">

                <candidate-row v-for="candidate in candidates"
                               :key="candidate.id"
                               :candidate="candidate"
                ></candidate-row>

                <writein-row v-if="writeInCandidates.length > 0"
                             v-for="candidate in writeInCandidates"
                             :candidate="candidate"
                             :key="candidate.id"
                ></writein-row>

            </div>


            <div class="card-body">
                <write-in-controls></write-in-controls>
            </div>

            <election-results-card></election-results-card>

            <div class="card-footer">
                <cast-ballot-button></cast-ballot-button>
            </div>

        </div>

        <div class="wrong-mode-message card" v-else>
            <div class="card-body">
                <p class="card-text">There is no pending election</p>
            </div>

        </div>


    </div>
</template>

<script>
import CandidateRow from "./candidate-row";
import MeetingMixin from "../../mixins/meetingMixin";
import MotionStoreMixin from "../../mixins/motionStoreMixin"
import {isReadyToRock} from '../../utilities/readiness.utilities'
import CastBallotButton from "./cast-ballot-button";
import OverselectionWarning from "./overselection-warning";
import MaxWinnersInstruction from "./max-winners-instruction";
import WriteinRow from "./writein-row";
import WriteInControls from "./write-in-controls";
import ElectionResultsCard from "./results/election-results-card";
import ModeMixin from "../../mixins/modeMixin";

export default {
    name: "election-card",
    components: {
        ElectionResultsCard,
        WriteInControls,
        WriteinRow, MaxWinnersInstruction, OverselectionWarning, CastBallotButton, CandidateRow
    },
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin, ModeMixin],

    data: function () {
        return {}
    },

    beforeRouteEnter (to, from, next) {
        next(vm => {
            // vm.setElection();

            window.console.log('entering election route. Mode:', vm.mode);

            // access to component instance via `vm`
        })
    },



    asyncComputed: {

        office: {
            get: function () {
                return this.motion;
            },
            default: null

        },

        writeInCandidates: {
            get: function () {
                return this.$store.getters.getWriteInCandidatesForCurrentOffice;
            },
            watch: ['candidates']
        },

        candidates: {

            get: function () {
                let me = this;
                if (isReadyToRock(this.meeting) && isReadyToRock(this.motion)) {

                    return me.$store.getters.getCandidatesForOffice(me.motion);

                }
                return []

                // //dev hackery
                //     let firstMotion = this.$store.getters.getStoredMotions;
                //
                //     if (firstMotion.length === 0) return [];
                //
                //     firstMotion = firstMotion[0];
                //     window.console.log(firstMotion, "first motion");
                //     //
                //     return this.$store.dispatch('setCurrentMotion', {
                //         meetingId: this.meeting.id,
                //         motionId: firstMotion.id,
                //     }).then(() => {
                //
                //         return me.$store.dispatch('loadElectionCandidates', me.motion.id).then(() => {
                //
                //             return me.$store.getters.getCandidatesForOffice(me.motion);
                //
                //         });
                //     })
                //
                //
                // }

            },

            watch: ['motion'],

            default: []

        },

        instructions: {
            get: function () {
                if (isReadyToRock(this.motion)) return this.motion.description;

                return ''
            },

            default: ''

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

    methods: {}
    ,

    mounted() {

//todo DEV ONLY
        let me = this;
        //parse data from page and store stuff
        let p = this.$store.dispatch('initialize');
        p.then(function () {
            // me.$router.push('meeting-home');
            // if (isReadyToRock(me.meeting)) {

            //dev hackery
            let firstMotion = me.$store.getters.getStoredMotions;

            if (firstMotion.length === 0) return [];

            firstMotion = firstMotion[0];
            window.console.log(firstMotion, "first motion");
            //
            me.$store.dispatch('setCurrentMotion', {
                meetingId: me.meeting.id,
                motionId: firstMotion.id,
            }).then(() => {

                me.$store.dispatch('loadElectionCandidates', me.motion.id).then(() => {

                    window.console.log('voteomatic', 'isReady', 159, me.isReady);
                });

            })


            // }

        });


        // this.$store.dispatch('loadElectionCandidates', this.motion.id);
    }

}
</script>

<style scoped>

</style>
