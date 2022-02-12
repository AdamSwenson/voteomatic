<template>
    <div class="election-card">
        <div class="election-complete-card card" v-if="isComplete">
            <div class="card-body">
                <p class="card-text">Thank you for voting!</p>
            </div>
        </div>

        <div class="card" v-else-if="isElection">

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

                <!--                Enable after VOT-60 is complete-->
                <candidate-row v-if="writeInCandidates.length > 0"
                               v-for="candidate in writeInCandidates"
                               :candidate="candidate"
                               :key="candidate.id"
                ></candidate-row>
                <overselection-warning></overselection-warning>

            </div>

            <!--            Enable after VOT-60 is complete-->
            <div class="card-body">
                <write-in-controls></write-in-controls>

                <p class="text-muted">You will confirm and record your selections later.</p>
            </div>


            <div class="card-footer">
                <div class="row">
                    <div class="col-md-4">
<!--                        <span class="text-left mr-5">-->
                            <button class="btn  btn-block"
                                v-if="showPreviousButton"
                                    v-on:click="handlePrevious">Previous office</button>
<!--                        </span>-->
                        </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
<!--                        <span class="text-right ml-3">-->
                            <button class="btn btn-success btn-block" v-on:click="handleNext">Next office</button>
<!--                        </span>-->
                    </div>
                </div>

            </div>
            <!--            <div class="card-footer">-->
            <!--                <cast-ballot-button></cast-ballot-button>-->
            <!--            </div>-->

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
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin"
import {isReadyToRock} from '../../../utilities/readiness.utilities'
import CastBallotButton from "./cast-ballot-button";
import OverselectionWarning from "./overselection-warning";
import MaxWinnersInstruction from "./max-winners-instruction";
import WriteinRow from "../../deprecated/writein-row";
import WriteInControls from "../write-in/write-in-controls";
import ElectionResultsCard from "../results/election-results-card";
import ModeMixin from "../../../mixins/modeMixin";

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
        return {
            randomizeCandidates: true
        }
    },

    beforeRouteEnter(to, from, next) {
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

                    let c = me.$store.getters.getCandidatesForOffice(me.motion);
                    if (me.randomizeCandidates) {
                        c = _.shuffle(c);
                    }

                    return c;
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

        isComplete: function () {
            return this.$store.getters.isElectionComplete;
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
        },

        /**
         * We make it look disabled when on the first in
         * the stack
         */
        showPreviousButton : function(){
            return this.$store.getters.getMotions.indexOf(this.$store.getters.getActiveMotion) > 0

        }
    },

    computed: {},

    methods: {
        handleNext: function () {
            this.$store.dispatch('nextOfficeInStack');
        },
        handlePrevious: function () {
            this.$store.dispatch('previousOffice');
        }

    }
    ,

    mounted() {
        let me = this;

        // me.$store.dispatch('loadElectionCandidates', me.motion.id).then(() => {
        //
        //     window.console.log('election-card', 'isReady', 159, me.isReady);
        // });
//
// //todo DEV ONLY
//         let me = this;
//         //parse data from page and store stuff
//         let p = this.$store.dispatch('initialize');
//         p.then(function () {
//             // me.$router.push('meeting-home');
//             // if (isReadyToRock(me.meeting)) {
//
//             //dev hackery
//             let firstMotion = me.$store.getters.getStoredMotions;
//
//             if (firstMotion.length === 0) return [];
//
//             firstMotion = firstMotion[0];
//             window.console.log(firstMotion, "first motion");
//             //
//             me.$store.dispatch('setCurrentMotion', {
//                 meetingId: me.meeting.id,
//                 motionId: firstMotion.id,
//             }).then(() => {
//
//                 me.$store.dispatch('loadElectionCandidates', me.motion.id).then(() => {
//
//                     window.console.log('voteomatic', 'isReady', 159, me.isReady);
//                 });
//
//             })


        // }

        // });


        // this.$store.dispatch('loadElectionCandidates', this.motion.id);
    }

}
</script>

<style scoped>

</style>
