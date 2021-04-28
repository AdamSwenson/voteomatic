<template>
    <div class="election-card card">

        <div class="card-header">
            <h1 class="card-title">{{ title }} <span class="text-danger"></span></h1>
        </div>

        <election-edit-card></election-edit-card>

        <office-setup-card v-if="showOfficeSetup"></office-setup-card>

    </div>

</template>

<script>

import CandidateRow from "../candidate-row";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import {isReadyToRock} from '../../../utilities/readiness.utilities';
import WriteinRow from "../writein-row";
import WriteInControls from "../write-in-controls";
import CreateElectionButton from "./create-election-button";
import CreateOfficeButton from "./create-office-button";
import MeetingEditCard from "../../meetings/meeting-edit-card";
import OfficeSetupCard from "./office-edit-card";
import CurrentCandidatesCard from "./current-candidates-card";
import AddCandidatesCard from "./candidate-pool-card";
import CandidatePoolCard from "./candidate-pool-card";
import OfficeEditCard from "./office-edit-card";
import ElectionEditCard from "./election-edit-card";

export default {
    name: "election-setup-card",
    components: {
        ElectionEditCard,
        OfficeEditCard,
        CandidatePoolCard,
        AddCandidatesCard,
        CurrentCandidatesCard, OfficeSetupCard, MeetingEditCard, CreateOfficeButton, CreateElectionButton
    },
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],

    data: function () {
        return {}
    },


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
