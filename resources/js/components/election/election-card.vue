<template>
    <div class="election-card card">

        <div class="card-header">
            <h2 class="card-title">{{ officeName }}</h2>
        </div>

        <!--        <div class="card-body instructions" v-if="instructions.length > 0">-->
        <!--            {{ instructions }}-->
        <!--        </div>-->

        <div class="card-body">
            <candidate-row v-for="candidate in candidates" :key="candidate.id" :candidate="candidate"></candidate-row>
        </div>


        <div class="card-footer">
            Buttons here

        </div>

    </div>
</template>

<script>
import CandidateRow from "./candidate-row";
import MeetingMixin from "../../mixins/meetingMixin";
import MotionStoreMixin from "../../mixins/motionStoreMixin"
import {isReadyToRock} from '../../utilities/readiness.utilities'

export default {
    name: "election-card",
    components: {CandidateRow},
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],


    data: function () {
        return {}
    },

    asyncComputed: {

        office: {
            get: function () {
                return this.motion;
            },
            default: null

        },

        candidates: {
            get: function () {
                let me = this;
                if (isReadyToRock(this.meeting)) {

                    //dev hackery
                    let firstMotion = this.$store.getters.getStoredMotions;

                    if(firstMotion.length === 0 ) return [];

                    firstMotion = firstMotion[0];
                    window.console.log(firstMotion, "first motion");
                    //
                    return this.$store.dispatch('setCurrentMotion', {
                        meetingId: this.meeting.id,
                        motionId: firstMotion.id,
                    }).then(() => {

                        return me.$store.dispatch('loadElectionCandidates', me.motion.id).then(() => {

                            return me.$store.getters.getCandidatesForOffice( me.motion);

                        });
                    })



                }

            },

            // watch: this.motion,

            default: []

        },
        instructions: {
            get: function () {
                if (isReadyToRock(this.motion)) return this.motion.description;

                return ''
            },

            default: ''

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

//todo DEV ONLY
        let me = this;
        //parse data from page and store stuff
        let p = this.$store.dispatch('initialize');
        p.then(function () {
            // me.$router.push('meeting-home');

            window.console.log('voteomatic', 'isReady', 159, me.isReady);
        });


        // this.$store.dispatch('loadElectionCandidates', this.motion.id);
    }

}
</script>

<style scoped>

</style>
