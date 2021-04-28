<template>

    <div class="meeting-edit-controls">
        <div class="row">
        <div class="col text-left">
            <create-office-button></create-office-button>

        </div>

        <div class="col text-right">
            <create-election-button></create-election-button>


            <button class="btn btn-warning"
                    v-on:click="handleEditButtonClick"
            >Edit current {{ type }}
            </button>

            <delete-meeting-button></delete-meeting-button>
            <delete-meeting-modal></delete-meeting-modal>
        </div>
        </div>
    </div>

</template>

<script>

import MeetingMixin from "../../../mixins/meetingMixin";
import DeleteMeetingButton from "../../meetings/delete-meeting-button";
import DeleteMeetingModal from "../../meetings/delete-meeting-modal";
import CreateElectionButton from "./create-election-button";
import CreateOfficeButton from "./create-office-button";

/**
 * These are abstracted out so can swap in a different
 * set of controls for elections if needed
 */
export default {
    name: "election-edit-controls",
    components: {CreateOfficeButton, CreateElectionButton, DeleteMeetingModal, DeleteMeetingButton},
    props: [],


    mixins: [MeetingMixin],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {},

    methods: {
        handleClick: function () {
            let me = this;
            let p = this.$store.dispatch('createMeeting');
            p.then(function () {
                me.showFields = true;
                me.showArea = 'create';
            })
        },


        handleEditButtonClick: function () {

            this.$emit('showArea', 'edit');
        }
    }

}
</script>

<style scoped>

</style>
