<template>

    <div class="meeting-edit-controls">
        <button class="btn btn-primary"
                v-on:click="handleClick"
        >Create new {{ type }}
        </button>

        <button class="btn btn-warning"
                v-on:click="handleEditButtonClick"
        >Edit current {{ type }}
        </button>

        <delete-meeting-button></delete-meeting-button>
        <delete-meeting-modal></delete-meeting-modal>

    </div>

</template>

<script>

import DeleteMeetingButton from "./delete-meeting-button";
import DeleteMeetingModal from "./delete-meeting-modal";
import MeetingMixin from "../../mixins/meetingMixin";

/**
 * These are abstracted out so can swap in a different
 * set of controls for elections if needed
 */
export default {
    name: "meeting-edit-controls",
    components: {DeleteMeetingModal, DeleteMeetingButton},
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
