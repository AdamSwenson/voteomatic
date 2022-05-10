<template>
    <div class="pmode-home card"
         v-bind:id="displayId"
    >

        <div class="card-body">
        <rezzie-display
            :motion="motion"
            parent-id="displayId"
        ></rezzie-display>
        </div>


        <div class="card-body">
            <text-stylers></text-stylers>
        </div>

<!--        <div class="card-body">-->
<!--            <rezzie-display-->
<!--                v-for="m in motions"-->
<!--                :key="m.id"-->
<!--                :motion="m"-->
<!--                parent-id="displayId"-->
<!--            ></rezzie-display>-->
<!--        </div>-->

    </div>
</template>

<script>
import MotionMixin from "../../mixins/motionStoreMixin";
import MeetingMixin from "../../mixins/meetingMixin";
import motionObjectMixin from "../../mixins/motionObjectMixin";
import RezzieDisplay from "./rezzie-display";
import TextStylers from "./text-stylers/text-stylers";

export default {
    name: "rezzie-view-home",
    components: {TextStylers, RezzieDisplay},
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
