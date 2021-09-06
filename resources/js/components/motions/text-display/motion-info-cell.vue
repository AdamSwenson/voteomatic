<template>
<div class="motion-info-cell">
    <motion-type-badge :motion="motion"></motion-type-badge>

    <motion-text-display :motionStyle="motionStyle" :motion="motion"></motion-text-display>

    <br/>

    <required-vote-badge v-if="showRequiredVoteBadge" :motion="motion"></required-vote-badge>

    <debatable-badge v-if="showDebatableBadge" :motion="motion"></debatable-badge>

    <motion-status-badge :is-passed="isPassed"></motion-status-badge>


    <!--    <div-->
<!--        v-if="isAmendment"-->
<!--        class="amendment-area "-->
<!--        v-bind:class="amendmentClass">-->

<!--        <motion-type-badge :motion="motion"></motion-type-badge>-->

<!--        <amendment-text-display-->
<!--            v-if="isAmendment"-->
<!--        ></amendment-text-display>-->


<!--        <br/>-->

<!--        <required-vote-badge v-if="! isComplete && ! motion.isSuperseded()"-->
<!--                             :motion="motion"></required-vote-badge>-->
<!--        -->
<!--        <debatable-badge v-if="!isComplete && ! motion.isSuperseded()" :motion="motion"></debatable-badge>-->

<!--        <motion-status-badge v-if="isComplete" :is-passed="isPassed"></motion-status-badge>-->

<!--    </div>-->

<!--    <div-->
<!--        class="procedural-subsidiary-area"-->
<!--        v-bind:class="proceduralStyle"-->
<!--        v-else-if="isProceduralSubsidiary"-->
<!--    >-->
<!--        <motion-type-badge :motion="motion"></motion-type-badge>-->

<!--        <span v-bind:class="motionStyle">   {{ motion.content }}   </span>-->

<!--        <br/>-->

<!--        <required-vote-badge v-if="! isComplete && ! motion.isSuperseded()"-->
<!--                             :motion="motion"></required-vote-badge>-->
<!--        <debatable-badge v-if="!isComplete && ! motion.isSuperseded()" :motion="motion"></debatable-badge>-->

<!--        <motion-status-badge :is-passed="isPassed"></motion-status-badge>-->

<!--    </div>-->



    </div>
</template>

<script>

import ChairMixin from "../../../mixins/chairMixin";
import AmendmentMixin from "../../../mixins/amendmentMixin";
import ProceduralMixin from "../../../mixins/proceduralMixin";
import MotionResultsMixin from "../../../mixins/motionResultsMixin";
import receiptMixin from "../../../mixins/receiptMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MotionMixin from "../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../mixins/meetingMixin";
import motionObjectMixin from "../../../mixins/motionObjectMixin";
import MotionStatusBadge from "../badges/motion-status-badge";
import DebatableBadge from "../badges/debatable-badge";
import RequiredVoteBadge from "../badges/required-vote-badge";
import MotionTextDisplay from "./motion-text-display";
import MotionTypeBadge from "../badges/motion-type-badge";

/**
 * Used in motion-select-area.
 * Contains all info about the motion.
 * Does not contain controls
 */
export default {
    name: "motion-info-cell",
    components: {MotionTypeBadge, MotionTextDisplay, RequiredVoteBadge, DebatableBadge, MotionStatusBadge},
    props: ['motion'],

    mixins: [ChairMixin, AmendmentMixin, ProceduralMixin, motionObjectMixin, MotionResultsMixin, receiptMixin],

    data: function () {
        return {}
    },

    asyncComputed: {



        amendmentStyle: function () {

            if (this.isSecondOrder) {
                return ' pl-5 ';
            }

            return ' pl-4 ';
        },

        /**
         * Whether the motion that has been handed to this
         * component is the one globally selected.
         * @returns {boolean}
         */
        isSelected: function () {
            if (_.isUndefined(this.selectedMotion) || _.isNull(this.selectedMotion)) return false

            return this.motion.id === this.selectedMotion.id
        },

        /**
         * The styling to apply to the motion text.
         *
         * NB, this is all calculated here rather than in the
         * display components since those get used in different contexts.
         *
         * @returns {string}
         */
        motionStyle: function () {
            let style = '';

            // Global classes
            if (this.isMotionComplete || this.isSuperseded) {
                style += ' text-muted ';
            }
            if (this.isSelected) {
                style += ' lead font-weight-bold ';
            }

            //Amendments
            if(this.isAmendment){
                style += this.amendmentStyle;
            }

            //Procedural
            if(this.isProcedural){
               style += this.proceduralStyle;
            }

            return style;

        },

        proceduralStyle: function () {
            switch (this.pendingMotionDegree) {
                case 2:
                    return ' pl-5 '
                    break;
                case  1:
                    return ' pl-4 '
                    break;
                case 0:
                    return ' ';
                    break;
                default :
                    return ' ';
            }
        },

        /**
         * The current globally active motion
         * @returns {any}
         */
        selectedMotion: function () {
            return this.$store.getters.getActiveMotion;
        },



        showDebatableBadge: function(){
            if(isReadyToRock(this.motion)) {
                return !this.isMotionComplete && !this.isSuperseded;
            }
        },

        showRequiredVoteBadge : function(){
            if(isReadyToRock(this.motion)){
                //Don't show on already passed motions
                return ! this.isMotionComplete && ! this.isSuperseded;
            }
        },



        showStatusBadge: function(){
            if(isReadyToRock(this.motion)) {
                return !this.isMotionComplete && !this.isSuperseded;
            }
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
