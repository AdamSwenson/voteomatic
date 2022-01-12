<template>
    <div class="election-setup-card card">

        <div class="chair" v-if="isChair">

            <event-edit-card v-if="isInEventEditingMode"></event-edit-card>

            <candidate-field-config-card v-if="isInEventEditingMode"></candidate-field-config-card>
        </div>


    </div>

</template>

<script>

import CandidateRow from "../candidate-row";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import {isReadyToRock} from '../../../utilities/readiness.utilities';
import WriteinRow from "../../deprecated/writein-row";
import WriteInControls from "../write-in/write-in-controls";
import CreateElectionButton from "./controls/create-election-button";
import CreateOfficeButton from "./controls/create-office-button";
import MeetingEditCard from "../../deprecated/meeting-setup-card";
import OfficeSetupCard from "./office-edit-card";
import CurrentCandidatesCard from "./current-candidates-card";
import AddCandidatesCard from "./candidate-pool-card";
import CandidatePoolCard from "./candidate-pool-card";
import OfficeEditCard from "./office-edit-card";
import ElectionEditCard from "../../deprecated/election-edit-card";
import ModeMixin from "../../../mixins/modeMixin";
import ElectionSetupControls from "./controls/election-setup-controls";
import EventEditCard from "../../controls/event-edit-card";
import EventDisplayCard from "../../common/event-display-card";
import ChairMixin from "../../../mixins/chairMixin";
import CandidateFieldConfigCard from "./candidate-field-config-card";

export default {
    name: "election-setup-card",

    components: {
        CandidateFieldConfigCard,
        EventDisplayCard,
        EventEditCard,
        ElectionSetupControls,
        ElectionEditCard,
        OfficeEditCard,
        CandidatePoolCard,
        AddCandidatesCard,
        CurrentCandidatesCard, OfficeSetupCard, MeetingEditCard, CreateOfficeButton, CreateElectionButton
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
