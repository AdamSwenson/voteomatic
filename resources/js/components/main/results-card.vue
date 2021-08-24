<template>
<div class="results-card card">

    <election-results-card v-if="isElection"></election-results-card>

    <motion-results-page v-else></motion-results-page>
</div>
</template>

<script>
import MeetingMixin from "../../mixins/meetingMixin";
import ChairMixin from "../../mixins/chairMixin";
import ModeMixin from "../../mixins/modeMixin";

import ElectionResultsCard from "../election/results/election-results-card";
import MotionResultsPage from "./motion-results-page";

export default {
    name: "results-card",
    components: {MotionResultsPage, ElectionResultsCard, },
    props: [],


    mixins: [MeetingMixin, ChairMixin, ModeMixin, ],

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
