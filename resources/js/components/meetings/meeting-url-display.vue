<template>

    <div class="meeting-link text-start mt-4" v-if="isChair">
<!--        <h3 class="card-title">LTI access link</h3>-->
        <h5 class="card-subtitle user-select-all">{{ meetingLink }}</h5>
        <p class="text-muted ">Use this link in creating the assignment for Canvas access.<br/>Ignore this if the voteomatic is not configured as an app in a Canvas class.</p>
    </div>

</template>

<script>

import MeetingMixin from '../../mixins/meetingMixin';
import ChairMixin from "../../mixins/chairMixin";

export default {
    name: "meeting-url-display",

    props: [],

    mixins: [MeetingMixin, ChairMixin],


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
