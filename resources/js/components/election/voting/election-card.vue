<template>
    <div class="election-card">
        <div class="election-complete-card card" v-if="isComplete">
            <div class="card-body">
                <p class="card-text">Thank you for voting!</p>
            </div>
        </div>

        <div class="card" v-else-if="isElection">

            <div class="card-header">
                <h1 class="card-title">{{ officeName }}</h1>
            </div>

            <max-winners-instruction></max-winners-instruction>

            <div class="card-body" v-if="showDescription">
                <p class="card-text ms-4 me-4">{{ motion.description }}</p>
            </div>

            <overselection-warning></overselection-warning>

            <!--        <div class="card-body instructions" v-if="instructions.length > 0">-->
            <!--            {{ instructions }}-->
            <!--        </div>-->

            <div class="alert alert-success" role="alert" v-if="hasUserVoted">
                <p class="card-text">You have voted on this. </p>
            </div>

            <div class="vote-controls" v-else>
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
                    <div class="row">
                        <div class="col-6">
                            <write-in-controls></write-in-controls>
                        </div>
                        <!--                        <div class="col-4"></div>-->
                        <div class="col-6 text-start">
                            <p class="text-muted">You will confirm and record your selections later.</p>
                        </div>
                    </div>
                </div>
            </div>
            <navigation-footer></navigation-footer>

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
import NavigationFooter from "../voter/navigation/navigation-footer";

export default {
    name: "election-card",
    components: {
        NavigationFooter,
        ElectionResultsCard,
        WriteInControls,
        WriteinRow, MaxWinnersInstruction, OverselectionWarning, CastBallotButton, CandidateRow
    },
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin, ModeMixin],

    data: function () {
        return {
            //Moved to use settings in VOT-272
            // randomizeCandidates: true
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

        hasUserVoted: function () {
            if (isReadyToRock(this.meeting) && isReadyToRock(this.motion)) {

                return this.$store.getters.hasVotedOnCurrentMotion;
            }
        },

        office: {
            get: function () {
                return this.motion;
            },
            default: null

        },

        writeInCandidates: {
            get: function () {
                if (isReadyToRock(this.meeting) && isReadyToRock(this.motion)) {

                    return this.$store.getters.getWriteInCandidatesForCurrentOffice;
                }
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
                    }else{
                        //VOT-272 Assuming that alphabetical is the opposite of random
                        c = _.sortBy(c, [function(o) { return o.last_name; }]);
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

        // instructions: {
        //     get: function () {
        //         if (isReadyToRock(this.motion)) return this.motion.description;
        //
        //         return ''
        //     },
        //
        //     default: ''
        //
        // },

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
         * Whether to randomize the candidates as they are displayed.
         * Returns true by default for backwards compatibility
         * @version Added in VOT-272
         * @returns {*|boolean}
         */
        randomizeCandidates: function(){
            let setting = this.$store.getters.getSettings;
            window.console.log('election-card', 'randomizeCandidates', 233, setting.settings.randomize_candidates);
return isReadyToRock(setting.settings.randomize_candidates) ? setting.settings.randomize_candidates : true;

        },


        /**
         * We make it look disabled when on the first in
         * the stack
         */
        showPreviousButton: function () {
            return this.$store.getters.getMotions.indexOf(this.$store.getters.getActiveMotion) > 0

        },

        showDescription: function () {
            return isReadyToRock(this.motion, 'description') && this.motion.description.length > 0;
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
