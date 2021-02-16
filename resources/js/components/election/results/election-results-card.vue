<template>
    <div class="election-results-card card">


        <div class="card-header">
            <h1 class="card-title">Results </h1>
        </div>

        <div class="card-body">

            <office-results-card v-for="motion in motions" :key="motion.id" :motion="motion"></office-results-card>


        </div>

    </div>
</template>
<div class="election-results-card card">


<div class="card-header">
    <h2 class="card-title">Results for the election </h2>
</div>

<div class="card-body">

    <office-results-card
        v-if="motions.length > 0"
        v-for="motion in motions"
        :key="motion.id"
        :motion="motion"
    ></office-results-card>

</div>


</div>
<script>
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import OfficeResultsCard from "./office-results-card";

export default {
    name: "election-results-card",
    components: {OfficeResultsCard},
    props: [],

    mixins: [MeetingMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        motions: {
            get: function () {
                return this.$store.getters.getMotions;
            },
            default: []


        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
