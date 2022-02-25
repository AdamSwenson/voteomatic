<template>
<div class="election-admin-card card">

    <div class="card-body">
        <start-election-button></start-election-button>
        <end-election-button></end-election-button>
        <release-results-button></release-results-button>
    </div>

</div>
</template>

<script>
import EndElectionButton from "./end-election-button";
import ReleaseResultsButton from "./release-results-button";
import StartElectionButton from "./start-election-button";
import AllReceiptsMixin from "../../../mixins/allReceiptsMixin";
import MeetingMixin from "../../../mixins/meetingMixin";
export default {
    name: "election-admin-card",
    components: {StartElectionButton, ReleaseResultsButton, EndElectionButton},
    props: [],



    mixins: [MeetingMixin],
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
