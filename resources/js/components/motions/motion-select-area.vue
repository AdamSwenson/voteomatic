<template>

    <li class="list-group-item "
        v-bind:class="styling">
        <div class="row">

            <div class="col-sm "
                 v-if="isChair"
            >
                <motion-select-button
                    v-if="isChair  "
                    :motion="motion"
                ></motion-select-button>

            </div>

            <div class="col ">

                <motion-info-cell :motion="motion"></motion-info-cell>

            </div>

            <div class="col-sm">

                <vote-nav-button
                    :motion="motion"
                    v-if="isSelected && ! isComplete && isVotingAllowed"
                ></vote-nav-button>

                <open-voting-button
                    v-if="isChair && isSelected && ! isComplete && ! isVotingAllowed"
                    :motion="motion"
                ></open-voting-button>


                <!--                v-if="isSelected && ! isComplete && ! hasVotedOnCurrentMotion"-->
                <end-voting-button
                    v-if="isChair && isSelected && ! isComplete && isVotingAllowed "
                    :motion="motion"
                ></end-voting-button>

                <results-nav-button
                    v-if="isSelected && isComplete"
                    :motion="motion"
                ></results-nav-button>


            </div>
        </div>
        <div class="row" v-if="showReceipt">
            <div class="col">
                <p><strong>Receipt: </strong> {{receipt}}  <info-tooltip :content="infoReceipt"></info-tooltip></p>
            </div>
        </div>
    </li>
</template>

<script>
import MotionSelectButton from "./motion-select-button";
import EndVotingButton from "./end-voting-button";
import * as routes from "../../routes";
import MotionStatusBadge from "./badges/motion-status-badge";
import VoteNavButton from "../navigation/vote-nav-button";
import ResultsNavButton from "../navigation/results-nav-button";
import ChairMixin from "../../mixins/chairMixin";
import AmendmentTextDisplay from "./text-display/amendment-text-display";
import AmendmentMixin from "../../mixins/amendmentMixin";
import MotionResultsMixin from '../../mixins/motionResultsMixin';
import ProceduralMixin from "../../mixins/proceduralMixin";
import receiptMixin from "../../mixins/receiptMixin";

// import AmendmentBadge from "./badges/amendment-badge";
import MotionTypeBadge from "./badges/motion-type-badge";
import RequiredVoteBadge from "./badges/required-vote-badge";
import DebatableBadge from "./badges/debatable-badge";
import OpenVotingButton from "./open-voting-button";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import InfoTooltip from "../messaging/info-tooltip";
import MainMotionTextDisplay from "./text-display/motion-text-display";
import MotionTextDisplay from "./text-display/motion-text-display";
import MotionInfoCell from "./text-display/motion-info-cell";

export default {
    name: "motion-select-area",
    components: {
        MotionInfoCell,
        MotionTextDisplay,
        MainMotionTextDisplay,
        InfoTooltip,
        OpenVotingButton,
        DebatableBadge,
        RequiredVoteBadge,
        MotionTypeBadge,
        // AmendmentBadge,
        AmendmentTextDisplay,
        ResultsNavButton, VoteNavButton, MotionStatusBadge, MotionSelectButton, EndVotingButton
    },
    props: ['motion'],
    mixins: [ChairMixin, AmendmentMixin, ProceduralMixin, MotionResultsMixin, receiptMixin],
    data: function () {
        return {
            amendmentTags: {
                inserted: 'amendment-added',
                struck: 'struck',

            },
            infoReceipt : "This receipt will only remain visible if you do not refresh the page in your browser. Since " +
                    "there is nothing tying it to your user id, it will be impossible to retrieve after you leave this page."

        }
    },
    asyncComputed: {

        amendmentClass: function () {

            if (this.isSecondOrder) {
                return 'pl-5 ' + this.motionStyle
            }
            return 'pl-4 ' + this.motionStyle;
        },

        hasVotedOnCurrentMotion: function () {
            return this.$store.getters.hasVotedOnCurrentMotion;
        },


        /**
         * Whether voting has been closed.
         * @returns {(function(): default.asyncComputed.motion.isComplete)|(function(): (__webpack_exports__.default.asyncComputed.motion.isComplete|undefined))|(function(): __webpack_exports__.default.asyncComputed.motion.isComplete)|(function(): (default.asyncComputed.motion.isComplete|undefined))}
         */
        isComplete: function () {
            return this.motion.isComplete;
        },


        // /**
        //  * Whether the motion has passed (after voting has been closed)
        //  */
        // isPassed: {
        //     get: function () {
        //         //must return undefined until actually loaded
        //         //otherwise the badge will be sad
        //         if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
        //
        //             let me = this;
        //             if (this.motion.isComplete) {
        //                 // return this.$store.dispatch('getResults', {motion: this.motion, setfalse)
        //                 //     .then(({passed, totalVotes}) => {
        //                 //         return passed;
        //                 //     });
        //                 return new Promise(((resolve, reject) => {
        //
        //                     let url = routes.results.getResults(me.motion.id);
        //
        //                     return Vue.axios.get(url)
        //                         .then((response) => {
        //                             return resolve(response.data.passed);
        //                         });
        //                 }));
        //             }
        //         }
        //
        //     },
        // },

        /**
         * Whether the motion that has been handed to this
         * component is the one globally selected.
         * @returns {boolean}
         */
        isSelected: function () {
            if (_.isUndefined(this.selectedMotion) || _.isNull(this.selectedMotion)) return false

            return this.motion.id === this.selectedMotion.id
        },


        isVotingAllowed: function () {
            return this.motion.isVotingAllowed;
        },


        /**
         * The styling to apply to the motion text
         * @returns {string}
         */
        motionStyle: function () {
            if (this.isComplete || this.motion.isSuperseded()) {
                return 'text-muted';
            }
            if (this.isSelected) {
                return 'lead font-weight-bold';
            }
        },

        proceduralStyle: function () {
            switch (this.pendingMotionDegree) {
                case 2:
                    return 'pl-5'
                    break;
                case  1:
                    return 'pl-4'
                    break;
                case 0:
                    return '';
                    break;
                default :
                    return '';
            }
        },

        /**
         * The current globally active motion
         * @returns {any}
         */
        selectedMotion: function () {
            return this.$store.getters.getActiveMotion;
        },

        showStatusBadge: function () {
            if (_.isUndefined(this.isPassed) || _.isNull(this.isPassed)) return false

            return true
        },

        showReceipt: function(){
          return isReadyToRock(this.vote);
        },

        styledResult: function () {
            if (_.isUndefined(this.isPassed) || _.isNull(this.isPassed)) return ''

            if (this.isPassed) {
                return "<span class='text-success'>Passed</span>";
            }

            if (!this.isPassed) {
                return "<span class='text-danger'>Failed</span>";
            }

        },

        styling: {
            get: function () {
                if (this.isSelected) {
                    return ' bg-info '
                }

            },
            default: ''
        },


    },
}
</script>

<style>

.amendment-added {
    text-decoration: underline;
}


</style>
