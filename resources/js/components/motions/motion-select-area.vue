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
                <span class="badge badge-warning"
                      v-if="isAmendment"
                >Amendment</span>

                <span v-bind:class="motionStyle">   {{ motion.content }}   </span>

                <motion-status-badge :is-passed="isPassed"></motion-status-badge>
            </div>

            <div class="col-sm">

                <vote-nav-button
                    :motion="motion"
                    v-if="isSelected && ! isComplete "
                ></vote-nav-button>

                <!--                v-if="isSelected && ! isComplete && ! hasVotedOnCurrentMotion"-->
                <end-voting-button
                    v-if="isSelected && ! isComplete && isChair"
                    :motion="motion"
                ></end-voting-button>

                <results-nav-button
                    v-if="isSelected && isComplete"
                    :motion="motion"
                ></results-nav-button>


            </div>
        </div>
    </li>
</template>

<script>
import MotionSelectButton from "./motion-select-button";
import EndVotingButton from "./end-voting-button";
import * as routes from "../../routes";
import MotionStatusBadge from "../text-display/motion-status-badge";
import VoteNavButton from "../navigation/vote-nav-button";
import ResultsNavButton from "../navigation/results-nav-button";
import ChairMixin from "../../mixins/chairMixin";

export default {
    name: "motion-select-area",
    components: {ResultsNavButton, VoteNavButton, MotionStatusBadge, MotionSelectButton, EndVotingButton},
    props: ['motion'],
    mixins: [ChairMixin],
    asyncComputed: {
        hasVotedOnCurrentMotion: function () {
            return this.$store.getters.hasVotedOnCurrentMotion;
        },

        isChair: function () {
            return this.$store.getters.getIsAdmin;
        },


        /**
         * Whether voting has been closed.
         * @returns {(function(): default.asyncComputed.motion.isComplete)|(function(): (__webpack_exports__.default.asyncComputed.motion.isComplete|undefined))|(function(): __webpack_exports__.default.asyncComputed.motion.isComplete)|(function(): (default.asyncComputed.motion.isComplete|undefined))}
         */
        isComplete: function () {
            return this.motion.isComplete;
        },

        isAmendment: function(){
            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.motion.isAmendment();
            }

        },

        /**
         * Whether the motion has passed (after voting has been closed)
         */
        isPassed: {
            get: function () {
                //must return undefined until actually loaded
                //otherwise the badge will be sad
                if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {

                    let me = this;
                    if (this.motion.isComplete) {
                        return new Promise(((resolve, reject) => {

                            let url = routes.results.getResults(me.motion.id);

                            return Vue.axios.get(url)
                                .then((response) => {
                                    return resolve(response.data.passed);
                                });
                        }));
                    }
                }

            },
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
         * The styling to apply to the motion text
         * @returns {string}
         */
        motionStyle: function () {
            if (this.isComplete) {
                return 'text-muted';
            }
            if (this.isSelected) {
                return 'lead font-weight-bold';
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

<style scoped>

</style>
