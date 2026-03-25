<template>

    <div class="election-setup-controls row">

        <div class="col ">
            <create-election-button></create-election-button>
            <span class="me-3"></span>

            <duplicate-election-button></duplicate-election-button>
            <span class="me-3"></span>

            <edit-election-button></edit-election-button>
            <span class="me-3"></span>

            <delete-election-button></delete-election-button>
            <delete-election-modal></delete-election-modal>

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
import DuplicateElectionButton from "./duplicate-election-button.vue";

/**
 * These are abstracted out so can swap in a different
 * set of controls for elections if needed
 */
export default {
    name: "election-setup-controls",
    components: {
        DuplicateElectionButton,
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


    computed: {},

    methods: {
    }

}
</script>

<style scoped>

</style>
