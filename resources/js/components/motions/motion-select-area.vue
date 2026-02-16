<template>

    <li class="motion-select-area list-group-item "
        v-bind:class="styling">
        <div class="row mt-2 mb-2">


            <div class="col-md-2 "
                 v-if="isChair && ! isInPublicPmode"
            >
                <motion-select-button
                    :motion="motion"
                ></motion-select-button>

            </div>

            <div class="col-md ">

                <motion-info-cell :motion="motion"></motion-info-cell>

            </div>

            <div class="col-md-2">
                <div class="d-grid gap-2 mb-2">
<!--                    v-if="isSelected && ! isComplete && isVotingAllowed && !isInPublicPmode"-->

                    <vote-nav-button
                        :motion="motion"
                        v-if=" ! isComplete && isVotingAllowed && !isInPublicPmode"
                    ></vote-nav-button>
                </div>

                <div class="d-grid gap-2 ">
                    <open-voting-button
                        v-if="isChair && isSelected && ! isComplete && ! isVotingAllowed"
                        :motion="motion"
                    ></open-voting-button>
                </div>

                <div class="d-grid gap-2 ">
                    <end-voting-button
                        v-if="isChair && isSelected && ! isComplete && isVotingAllowed "
                        :motion="motion"
                    ></end-voting-button>
                </div>

                <div class="d-grid gap-2 mb-2">
                    <!--                    v-if="isSelected && isComplete && ! isInPublicPmode"-->

                    <results-nav-button
                        v-if=" isComplete && ! isInPublicPmode"
                        :motion="motion"

                    ></results-nav-button>
                </div>

                <div class="d-grid gap-2 ">

                    <view-full-text-button v-if="isResolution" :motion="motion"></view-full-text-button>
                    <view-full-text-modal v-if="isResolution" :motion="motion"></view-full-text-modal>
                </div>

            </div>
        </div>

<!--        <div class="row" v-if="showReceipt">-->
            <dl class="row" v-if="showReceipt">
                <dt class="col-sm-2">Receipt</dt>
                <dd class="col-sm-10 "><span class="user-select-all me-3">{{receipt}}</span> <info-tooltip :content="infoReceipt"></info-tooltip></dd>
                <dt class="col-sm-2" v-if="showVoteContent">Your vote</dt>
                <dd class="col-sm-10" v-if="showVoteContent">{{voteContent}}</dd>
            </dl>

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
import PublicPModeMixin from "../../mixins/publicPmodeMixin";

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
import AbortVotingButton from "./abort-voting-button.vue";
import VoteCard from "../main/vote-card.vue";
import ViewFullTextButton from "./view-full-text/view-full-text-button.vue";
import ViewFullTextModal from "./view-full-text/view-full-text-modal.vue";
import motionObjectMixin from "../../mixins/motionObjectMixin";

export default {
    name: "motion-select-area",
    components: {
        ViewFullTextModal,
        ViewFullTextButton,
        VoteCard,
        AbortVotingButton,
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
    mixins: [ChairMixin, AmendmentMixin, ProceduralMixin, MotionResultsMixin, PublicPModeMixin, receiptMixin, motionObjectMixin],
    data: function () {
        return {
            amendmentTags: {
                inserted: 'amendment-added',
                struck: 'struck',

            },
            infoReceipt: "This receipt will only remain visible if you do not refresh the page in your browser. Since " +
                "there is nothing tying it to your user id, it will be impossible to retrieve after you leave this page."

        }
    },


    computed: {
        // asyncComputed: {

        amendmentClass: function () {

            if (this.isSecondOrder) {
                return 'ps-5 ' + this.motionStyle
            }
            return 'ps-4 ' + this.motionStyle;
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
                return 'lead fw--bold';
            }
        },

        proceduralStyle: function () {
            switch (this.pendingMotionDegree) {
                case 2:
                    return 'ps-5'
                    break;
                case  1:
                    return 'ps-4'
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

        showReceipt: function () {
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
                    return ' border border-info border-4 '
                    // bg-info
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
