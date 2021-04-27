<template>

    <div class="meeting-edit-controls">
        <create-election-button></create-election-button>


        <button class="btn btn-warning"
                v-on:click="handleEditButtonClick"
        >Edit current {{ type }}
        </button>

        <delete-meeting-button></delete-meeting-button>
        <delete-meeting-modal></delete-meeting-modal>

    </div>

</template>

<script>

import MeetingMixin from "../../../mixins/meetingMixin";
import DeleteMeetingButton from "../../meetings/delete-meeting-button";
import DeleteMeetingModal from "../../meetings/delete-meeting-modal";
import CreateElectionButton from "./create-election-button";

/**
 * These are abstracted out so can swap in a different
 * set of controls for elections if needed
 */
export default {
    name: "election-edit-controls",
    components: {CreateElectionButton, DeleteMeetingModal, DeleteMeetingButton},
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
