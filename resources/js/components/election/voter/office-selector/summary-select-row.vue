<template>

    <!-- switched to button in VOT-288 to fix VOT-300 -->
    <!--    <a href="#"-->
    <button type="button"
            class="list-group-item list-group-item-action"
            v-bind:class="styling"
            v-bind:aria-current="ariaStatus"
            v-on:click="handleSelect">
        <span v-bind:class="textStyling">
            <i class="bi-save" aria-hidden="true"></i>   Review and record selections
        </span>
    </button>
</template>

<script>
import MotionMixin from "../../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../../mixins/meetingMixin";
import motionObjectMixin from "../../../../mixins/motionObjectMixin";
import {isReadyToRock} from "../../../../utilities/readiness.utilities";

export default {
    name: "summary-select-row",

    /**
     * motion (the currently selected motion) will be available
     * via mixin. Hence this naming
     */
    props: [],


    // mixins: [MeetingMixin, motionObjectMixin],

    data: function () {
        return {}
    },


    computed: {
    // asyncComputed: {
        // officeName: function(){
        //     return this.motion.content;
        // },

        /**
         * Returns true if the user has already voted on this office
         */
        hasVoted: function(){
// return true;
        },

        /**
         * Returns true if there's a problem which
         * should prevent navigation or submission, e.g.,
         * overselection
         */
        isError : function(){
            // if(!isReadyToRock(this.motion)) return '';
            //
            // return this.$store.getters.showOverSelectionWarningForMotion(this.motion);

            // return true;
        },

        /**
         * Whether the motion that has been handed to this
         * component is the one globally selected.
         * @returns {boolean}
         */
        isSelected: function () {
            return this.$store.getters.isSummarySubmitCardVisible;
            // if (_.isUndefined(this.selectedMotion) || _.isNull(this.selectedMotion)) return false
            //
            // return this.motion.id === this.selectedMotion.id
        },

        //
        // isSelected: {get:function() {
        //         if (isReadyToRock(this.motion) && isReadyToRock(this.myMotion)) {
        //             window.console.log('rrtr', this.motion, this.myMotion);
        //             return this.motion === this.myMotion.id;
        //         }
        //         return false;
        //     }
        // },

        // /**
        //  * The current globally active motion
        //  * @returns {any}
        //  */
        // selectedMotion: function () {
        //     return this.$store.getters.getActiveMotion;
        // },

        ariaStatus : function (){
            if (this.isSelected) {
                return 'true'
                // return ' bg-info '
            }
            return 'false'
        },

        styling: {
            get: function () {
                if (this.isSelected) {
                    return ' active '
                    // return ' bg-info '
                }

            },
            default: ''
        },
        //
        // styling: {
        //     get: function () {
        //         if (this.isSelected) {
        //             return ' bg-info '
        //         }
        //
        //     },
        //     default: ''
        // },

        textStyling : function(){
            if(this.hasVoted) return 'text-muted';

            if(this.isError) return 'text-danger'
        }

    },


    methods: {
        handleSelect: function(){
            // window.console.log('setting office ');
            this.$store.commit('showSummarySubmitCard');
        }
    }

}
</script>

<style scoped>

</style>
