<template>
    <div class="rezzie-view-home"
         v-bind:id="displayId"
    >
        <rezzie-display
            v-for="m in motions"
            :key="m.id"
            :motion="m"
            parent-id="displayId"
        ></rezzie-display>


    </div>
</template>

<script>
import MotionMixin from "../../mixins/motionStoreMixin";
import MeetingMixin from "../../mixins/meetingMixin";
import motionObjectMixin from "../../mixins/motionObjectMixin";
import RezzieDisplay from "./rezzie-display";

export default {
    name: "rezzie-view-home",
    components: {RezzieDisplay},
    props: [],

    mixins: [MotionMixin, MeetingMixin, motionObjectMixin],

    data: function () {
        return {
            displayId: function () {
                return 'rezzie-view-home';
            }
        }
    },

    asyncComputed: {
        motions: function () {
            return _.reverse(this.$store.getters.getMotions);
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
