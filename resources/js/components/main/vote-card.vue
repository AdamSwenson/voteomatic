<template>
<div class="vote-card card router-tab-touching-card">

    <election-card v-if="isElection"></election-card>

    <vote-page v-else></vote-page>


</div>
</template>

<script>
import MeetingMixin from "../../mixins/meetingMixin";
import ChairMixin from "../../mixins/chairMixin";
import ElectionCard from "../election/voting/election-card";
import ModeMixin from "../../mixins/modeMixin";

import VotePage from "../main/vote-page";

export default {
    name: "vote-card",

    props: [],
components : {VotePage, ElectionCard},
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
