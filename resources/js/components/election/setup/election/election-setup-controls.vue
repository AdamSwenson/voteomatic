<template>

    <div class="election-setup-controls row">
<!--        <div class="col-lg-2 text-start">-->
<!--            <create-office-button></create-office-button>-->
<!--        </div>-->

        <div class="col-lg-6 ">
            <create-election-button></create-election-button>

            <edit-election-button></edit-election-button>

            <delete-election-button></delete-election-button>
            <delete-election-modal></delete-election-modal>

        </div>

        <div class="col-lg-6 text-end">
<!--            <mode-toggle-switch></mode-toggle-switch>-->
        </div>

    </div>

</template>

<script>

import MeetingMixin from "../../../../mixins/meetingMixin";
import DeleteMeetingButton from "../../../meetings/controls/delete-meeting-button";
import DeleteMeetingModal from "../../../meetings/controls/delete-meeting-modal";
import CreateElectionButton from "./create-election-button";
import CreateOfficeButton from "../offices/create/create-office-button";
import ModeMixin from "../../../../mixins/modeMixin";
import DeleteElectionModal from "./delete-election-modal";
import DeleteElectionButton from "./delete-election-button";
import EditElectionButton from "./edit-election-button";
import CreateMeetingButton from "../../../meetings/controls/create-meeting-button";
import ModeToggleSwitch from "../../../controls/mode-toggle-switch";

/**
 * These are abstracted out so can swap in a different
 * set of controls for elections if needed
 */
export default {
    name: "election-setup-controls",
    components: {
        ModeToggleSwitch,
        CreateMeetingButton,
        EditElectionButton,
        DeleteElectionButton,
        DeleteElectionModal, CreateOfficeButton, CreateElectionButton, DeleteMeetingModal, DeleteMeetingButton
    },
    props: [],


    mixins: [MeetingMixin, ModeMixin],


    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {},

    methods: {
//         handleClick: function () {
//             let me = this;
//             let p = this.$store.dispatch('createMeeting');
//             p.then(function () {
//                 me.showFields = true;
//                 me.showArea = 'create';
//             })
//         },
//
//
//         handleEditButtonClick: function () {
// this.setElection();
// this.showArea = 'edit';
//
//             this.$emit('showArea', 'edit');
//
//         }
    }

}
</script>

<style scoped>

</style>
