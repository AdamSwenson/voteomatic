<template>
<div class="card election-setup">

    <div class="card-body">
        <meeting-edit-card v-bind:should-show-fields="true"></meeting-edit-card>
    </div>

    <div class="card-body">
        <office-list-card></office-list-card>
    </div>
</div>
</template>

<script>
import OfficeListCard from "../election/setup/office-list-card";
import MeetingEditCard from "./meeting-setup-card";
export default {
    name: "election-edit-card",
    components: {MeetingEditCard, OfficeListCard},
    props: [],

    mixins: [],

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
