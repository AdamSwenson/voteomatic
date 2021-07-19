<template>
    <div class="ballot-card card">

        <office-setup-card v-if="isElection"></office-setup-card>

        <motion-setup v-else></motion-setup>

    </div>
</template>

<script>
import MeetingMixin from "../../mixins/meetingMixin";
import ChairMixin from "../../mixins/chairMixin";
import ModeMixin from "../../mixins/modeMixin";
import MotionsCard from "../motions/motions-card";
import MotionSetup from "./chair/motion-setup";
import OfficeSetupCard from "../election/setup/office-setup-card";

export default {
    name: "ballot-setup-card",
    components: {OfficeSetupCard, MotionSetup, MotionsCard},
    props: [],

    mixins: [MeetingMixin, ChairMixin, ModeMixin],


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
