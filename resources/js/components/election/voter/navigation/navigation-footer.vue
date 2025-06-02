<template>
    <div class="card-footer">
        <div class="row">
<!--<div class="grid">-->
<!--    <div class="g-col-4">-->
            <div class="col-4 text-start">
                <button class="btn btn-info btn-block"
                        v-if="showPreviousButton"
                        v-on:click="handlePrevious"
                ><i class="bi-chevron-double-left"></i>       Previous
                </button>
            </div>

<!--    <div class="g-col-4"></div>-->
            <div class="col-4"></div>
<!--<div class="g-col-4">-->
            <div class="col-4 text-end">
                <button class="btn btn-info btn-block"
                        v-if="showNextButton"
                        v-on:click="handleNext"
                >Next       <i class="bi-chevron-double-right"></i>
                </button>
            </div>

        </div>

    </div>
</template>

<script>
import {isReadyToRock} from "../../../../utilities/readiness.utilities";
import MeetingMixin from "../../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../../mixins/motionStoreMixin";
import ModeMixin from "../../../../mixins/modeMixin";

export default {
    name: "navigation-footer",

    props: [],

    mixins: [MeetingMixin, MotionStoreMixin, ModeMixin],
    data: function () {
        return {}
    },

    asyncComputed: {
        /**
         * We hide it on the first in
         * the stack
         */
        showPreviousButton: function () {
            return this.$store.getters.getMotions.indexOf(this.$store.getters.getActiveMotion) > 0
        },

        /**
         * When on the summary page, the user will only see the previous button
         */
        showNextButton: function () {
            return ! this.$store.getters.isSummarySubmitCardVisible;
        },

        showDescription: function () {
            return isReadyToRock(this.motion, 'description') && this.motion.description.length > 0;
        }
    },

    computed: {},

    methods: {
        handleNext: function () {
            this.$store.dispatch('nextOfficeInStack');
        },
        handlePrevious: function () {
            this.$store.dispatch('previousOffice');
        }

    }

}
</script>

<style scoped>

</style>
