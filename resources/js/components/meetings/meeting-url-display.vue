<template>

    <div class="meeting-link text-left mt-4" v-if="isChair">
        <h5 class="card-subtitle user-select-all">{{ meetingLink }}</h5>
        <p class="text-muted ">Use this link in creating the Canvas assignment.</p>
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
