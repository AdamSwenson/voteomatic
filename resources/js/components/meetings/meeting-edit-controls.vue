<template>
  <!--  <div class="btn-toolbar justify-content-between" r-->
  <!--       role="toolbar"-->
  <!--       aria-label="Controls for meeting"-->
  <!--  >-->
  <!--    <div class="btn-group"-->
  <!--         role="group"-->
  <!--         aria-label="Creation, editing, and deletion controls"-->
  <!--    >-->
  <!--      <create-meeting-button></create-meeting-button>-->

  <!--      <meeting-edit-button></meeting-edit-button>-->

  <!--      <delete-meeting-button></delete-meeting-button>-->
  <!--      <delete-meeting-modal></delete-meeting-modal>-->
  <!--    </div>-->

  <!--    <mode-toggle-switch></mode-toggle-switch>-->


  <div class="meeting-edit-controls row">
    <div class="col ">
      <create-meeting-button></create-meeting-button>

      <meeting-edit-button></meeting-edit-button>

      <delete-meeting-button></delete-meeting-button>
      <delete-meeting-modal></delete-meeting-modal>
    </div>

    <div class="col text-end">
      <mode-toggle-switch></mode-toggle-switch>
    </div>
  </div>

</template>

<script>

import DeleteMeetingButton from "./controls/delete-meeting-button";
import DeleteMeetingModal from "./controls/delete-meeting-modal";
import MeetingMixin from "../../mixins/meetingMixin";
import ModeMixin from "../../mixins/modeMixin";
import ModeToggleSwitch from "../controls/mode-toggle-switch";
import CreateMeetingButton from "./controls/create-meeting-button";
import MeetingEditButton from "./controls/meeting-edit-button";

/**
 * These are abstracted out so can swap in a different
 * set of controls for elections if needed
 */
export default {
  name: "meeting-edit-controls",
  components: {MeetingEditButton, CreateMeetingButton, ModeToggleSwitch, DeleteMeetingModal, DeleteMeetingButton},
  props: [],

  mixins: [MeetingMixin, ModeMixin],


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
        this.setSetup();
        // me.showFields = true;
        // me.showArea = 'create';
      })
    },


    handleEditButtonClick: function () {
      this.setSetup();
      // this.$emit('showArea', 'edit');
    }
  }

}
</script>

<style scoped>

</style>
