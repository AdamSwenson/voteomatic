<template>
    <component :is="componentType"
               :amendment-id="amendmentId"
    >&nbsp;<slot></slot>
<!--        <slot v-slot="slotProps"></slot>-->
    </component>
    <!--    <component :is="componentType" :text="text" :amendment-id="amendmentId"></component>-->
</template>

<script>
import InsertFailed from "./insert-failed";
import InsertPassed from "./insert-passed";
import InsertPending from "./insert-pending";
import InsertPendingSuperseded from "./insert-pending-superseded";
import StrikePending from "./strike-pending";
import StrikeFailed from "./strike-failed";
import StrikePassed from "./strike-passed";
import StrikePendingSuperseded from "./strike-pending-superseded";
import MotionMixin from "../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../mixins/meetingMixin";
import motionObjectMixin from "../../../mixins/motionObjectMixin";
import UnstyledText from "./unstyled-text";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "text-styler-factory",
    components: {
        UnstyledText,
        StrikePendingSuperseded,
        StrikePassed,
        StrikeFailed, StrikePending, InsertPendingSuperseded, InsertPending, InsertPassed, InsertFailed
    },

    /**
     * showOnlyPending prevents display of past amendments
     * it will be true when used in the vote display and regular display.
     * it will be false when used in pmode.
     */
    props: {
        type: {type: String},
        // text: {type: String},
        amendmentId: {type: [Number, String]},
        //optional (if secondary exists)
        // secondaryAmendmentType,
        // secondaryAmendmentId,
        // secondaryAmendmentText
        // showOnlyPending: {type: Boolean, default: false}
    },

    // mixins: [MotionMixin, motionObjectMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        // sp : function(){
        //     return this.default.slotProps;
        // },

        amendment: function () {
            return this.$store.getters.getMotionById(this.amendmentId);
        },

        superseding: function(){
            if(isReadyToRock(this.amendment, 'superseded_by')){
                return this.$store.getters.getMotionById(this.amendment.superseded_by);
            }
        },

        componentType: function () {
            if (!isReadyToRock(this.currentMotion) || !isReadyToRock(this.amendment)) return UnstyledText;


            // -------------------- Current amendment
            if (this.isPending) {

                if (this.type === 'insert') return InsertPending;

                if (this.type === 'strike') return StrikePending;

            }

            // -------------------- Completed amendments
            if (this.isComplete) {

                if (this.type === 'insert' && this.passed) return InsertPassed;

                if (this.type === 'insert' && !this.passed) return InsertFailed;

                if (this.type === 'strike' && this.passed) return StrikePassed;

                if (this.type === 'strike' && !this.passed) return StrikeFailed;

            }

            // --------------------- Superseded pending
            //todo
            if (this.isPendingSuperseded) {
                if (this.type === 'insert') return InsertPendingSuperseded;
                if (this.type === 'strike') return StrikePendingSuperseded;
            }


            //If we are only to show pending stuff
            if (this.showOnlyPending && !this.isPending) return UnstyledText;

            //
            // switch (this.amendmentId) {
            //
            //     case this.showOnlyPending && !this.isPending:
            //         return UnstyledText;
            //         break;
            //
            //     // -------------------- Current amendment
            //     case this.type === 'insert' && this.isPending:
            //         return InsertPending;
            //         break;
            //
            //     case this.type === 'strike' && this.isPending:
            //         return StrikePending;
            //         break;
            //
            //
            //     // -------------------- Completed amendments
            //     case this.type === 'insert' && this.amendment.is_complete && this.passed:
            //         return InsertPassed;
            //         break;
            //
            //     case this.type === 'insert' && this.amendment.is_complete && !this.passed:
            //         return InsertFailed;
            //         break;
            //
            //     case this.type === 'strike' && this.amendment.is_complete && this.passed:
            //         return StrikePassed;
            //         break;
            //
            //     case this.type === 'strike' && this.amendment.is_complete && !this.passed:
            //         return StrikeFailed;
            //         break;
            //`
            //     // --------------------- Superseded pending


            // }


        },

        currentMotion: function () {
            return this.$store.getters.getActiveMotion;

        },

        /**
         * We can't just look at the isComplete
         * property of the amendment because if
         * it was superseded, that might not be set
         */
        isComplete: function(){
            if(isReadyToRock(this.amendment)){
                if(isReadyToRock(this.superseding)) return this.superseding.isComplete;
                return this.amendment.isComplete;
            }
        },

        /**
         * Whether the amendment is currently pending vote
         * (returns false if passed or superseded)
         * @returns {boolean}
         */
        isCurrentMotion: function () {
            if (!isReadyToRock(this.currentMotion)) return false;
            return this.currentMotion.id === this.amendmentId;
        },

        /**
         * True only if currently selected motion and
         * voting is not complete
         */
        isPending: function () {
            return this.isCurrentMotion && !this.amendment.is_complete;
        },

        isPendingSuperseded: function () {
            if (!isReadyToRock(this.amendment)) return false;
            return !this.isCurrentMotion && !this.amendment.is_complete;
        },


        passed: function () {
            if(isReadyToRock(this.superseding)) return this.$store.getters.getMotionPassed(this.superseding.id);
            return this.$store.getters.getMotionPassed(this.amendmentId);
        },

        showOnlyPending: function () {
            return !this.$store.getters.isInPublicPmode;
        },


        //
        // /**
        //  * Gets all secondary amendments which apply to
        //  * this amendment
        //  * @returns {*[]|*}
        //  */
        // secondaryAmendments: function () {
        //     if (!isReadyToRock(this.amendment)) return [];
        //
        //     let secondaries = this.$store.getters.getByAppliesToId(this.amendment);
        //     if (secondaries.length === 0) return [];
        //
        //     return secondaries.filter((m) => {
        //         return m.isAmendment();
        //     });
        // },


    },

    methods: {}

}
</script>

<style scoped>

</style>
