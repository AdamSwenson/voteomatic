<template>


<div class="setup-controls">

    <election-setup-controls v-if="isElection"></election-setup-controls>

    <!--    Default so that can always set up something-->
    <meeting-edit-controls v-else></meeting-edit-controls>

</div>



</template>

<script>

import MeetingMixin from "../../mixins/meetingMixin";
import ModeMixin from "../../mixins/modeMixin";
import ElectionSetupControls from "../election/setup/controls/election-setup-controls";
import MeetingEditControls from "../meetings/meeting-edit-controls";

/**
 * A mode agnostic set of buttons for setting up meetings and elections
 * It displays the appropriate buttons for the mode
 */
export default {
    name: "setup-controls",
    components: {MeetingEditControls, ElectionSetupControls},
    props: [],

    mixins: [MeetingMixin, ModeMixin],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
