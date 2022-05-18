<template>
    <div class="pmode-home card mt-1"
         v-bind:id="displayId"
    >
        <div class="row">
            <div class="col-lg-6">


                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Amendment History (Experimental)</h3>
                    </div>

                    <div class="card-body">

                        <rezzie-display
                            v-for="m in motions"
                            :key="m.id"
                            :motion="m"
                            parent-id="displayId"
                        ></rezzie-display>
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Legend</h4>
                    <pmode-legend></pmode-legend>
                </div>
            </div>

            <div class="col-lg-6">
<!--                <p class="text-end"><whatis></whatis></p>-->

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">Motion stack</h3>
                    </div>

                    <div class="card-body">
                        <div class="card-text">
                            <ul class="list-group list-group-flush">

                                <motion-select-area v-for="m in motionStack"
                                                    :motion="m"
                                                    :key="m.id"
                                ></motion-select-area>

                            </ul>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</template>

<script>
import MotionMixin from "../../mixins/motionStoreMixin";
import MeetingMixin from "../../mixins/meetingMixin";
import motionObjectMixin from "../../mixins/motionObjectMixin";
import RezzieDisplay from "./rezzie-display";
import TextStylers from "./text-stylers/text-stylers";
import MotionsCard from "../motions/motions-card";
import MotionSelectArea from "../motions/motion-select-area";
import PmodeLegend from "./informational/pmode-legend";
import Whatis from "./informational/whatis";

export default {
    name: "pmode-home",
    components: {Whatis, PmodeLegend, MotionSelectArea, MotionsCard, TextStylers, RezzieDisplay},
    props: [],

    mixins: [MotionMixin, MeetingMixin, motionObjectMixin],

    data: function () {
        return {
            displayId: function () {
                return this.name;
            }
        }
    },

    asyncComputed: {
        motions: function () {
            return this.$store.getters.getResolutionsForPModeDisplay;
            // return _.reverse(this.$store.getters.getMotions);
        },

        motionStack: function () {
            let m = this.$store.getters.getStoredMotions;
            if (_.isUndefined(m)) return [];

            //Display them in FILO order
            m = _.sortBy(m, ['id']);
            m = _.reverse(m);

            return m;
        },

    },

    computed: {},

    methods: {},
    mounted() {
        let me = this;
        this.$nextTick(function () {

        });
    }
}
</script>

<style scoped>

</style>
