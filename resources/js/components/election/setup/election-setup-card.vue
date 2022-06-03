<template>
    <div class="election-setup-card card">

        <div class="non-chair" v-if="! isChair">
            <election-display-card></election-display-card>
        </div>

        <div class="chair" v-else>

            <event-edit-card v-if="isInEventEditingMode"></event-edit-card>
            <election-display-card v-else></election-display-card>

            <candidate-field-config-card v-if="isInEventEditingMode"></candidate-field-config-card>

        </div>


    </div>

</template>

<script>
import {isReadyToRock} from '../../../utilities/readiness.utilities';

import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import ModeMixin from "../../../mixins/modeMixin";
import ChairMixin from "../../../mixins/chairMixin";

import EventEditCard from "../../controls/event-edit-card";
import CandidateFieldConfigCard from "./candidate-field-config-card";
import ElectionDisplayCard from "./election-display-card";

export default {
    name: "election-setup-card",

    components: {
        ElectionDisplayCard,
        CandidateFieldConfigCard,

        EventEditCard,

        // ElectionEditCard,
        // OfficeEditCard,
        // CandidatePoolCard,
        // AddCandidatesCard,
        // CurrentCandidatesCard,
        // OfficeSetupCard,
        // MeetingEditCard,
        // CreateOfficeButton,
        // CreateElectionButton
    },
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin, ModeMixin, ChairMixin,],

    data: function () {
        return {}
    },

    // beforeRouteEnter (to, from, next) {
    //     next(vm => {
    //
    //         window.console.log('entering election setup route. Mode:', vm.mode);
    //
    //         // access to component instance via `vm`
    //     })
    // },
    //
    // beforeRouteUpdate (to, from, next) {
    //
    //         // this.setElection();
    //
    //         window.console.log('entering election setup update route. Mode:', vm.mode);
    //     next();
    // },


    asyncComputed: {
        electionName: function () {
            if (!isReadyToRock(this.meeting)) return "Election setup"
            return this.meeting.name;
        },

        office: {
            get: function () {
                return this.motion;
            },
            default: null

        },

        instructions: {
            get: function () {
                if (isReadyToRock(this.motion)) return this.motion.description;

                return ''
            },

            default: ''

        },


        showOfficeSetup: function () {
            return isReadyToRock(this.motion);
        },

        title: function () {
            if (isReadyToRock(this.motion) && isReadyToRock(this.motion.name) && this.motion.name.length > 0) return this.motion.name;
            return "Setup election";
        }
    },

    computed: {},

    methods: {},

    mounted() {
//         if (isReadyToRock(this.meeting) && this.meeting.type === 'meeting') {
//             //todo should not access the election if there's not an election set
//             // this.$router.push('home');
//         }
//
//
// //todo DEV ONLY only needed if loading on blade template
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
//
//
//             // }
//
//         });


        // this.$store.dispatch('loadElectionCandidates', this.motion.id);
    }

}
</script>

<style scoped>

</style>
