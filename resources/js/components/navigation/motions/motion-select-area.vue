<template>

    <li class="list-group-item "
        v-bind:class="styling">

        <motion-select-button
        v-if="isChair"
            :motion="motion"
        ></motion-select-button>

        <vote-nav-button
            v-if="isSelected && ! isComplete"
        ></vote-nav-button>

        <span v-bind:class="motionStyle">
        {{ motion.content }}
        </span>

        <motion-status-badge :is-passed="isPassed"></motion-status-badge>

        <end-voting-button
            v-if="isSelected && ! isComplete && isChair"
            :motion="motion"
        ></end-voting-button>
    </li>
</template>

<script>
import MotionSelectButton from "./motion-select-button";
import EndVotingButton from "./end-voting-button";
import * as routes from "../../../routes";
import MotionStatusBadge from "../../text-display/motion-status-badge";
import VoteNavButton from "../../controls/vote-nav-button";

export default {
    name: "motion-select-area",
    components: {VoteNavButton, MotionStatusBadge, MotionSelectButton, EndVotingButton},
    props: ['motion'],

    asyncComputed: {

        isChair : function(){
            return this.$store.getters.getIsAdmin;
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
                // if (_.isUndefined(this.isPassed) || _.isNull(this.isPassed)) return ''
                //
                // if (this.isPassed) {
                //     return 'bg-success';
                // }
                //
                // if (!this.isPassed) {
                //     return 'bg-danger'
                // }

            },
            default: ''
        },

        showStatusBadge: function () {
            if (_.isUndefined(this.isPassed) || _.isNull(this.isPassed)) return false

            return true

        },


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

        selectedMotion: function () {
            return this.$store.getters.getActiveMotion;
        },

        isSelected: function () {

            if (_.isUndefined(this.selectedMotion) || _.isNull(this.selectedMotion)) return false

            return this.motion.id === this.selectedMotion.id
        },

        isComplete: function () {
            return this.motion.isComplete;
        },

        motionStyle: function () {
            if (this.isComplete) {
                return 'text-muted';
            }
            if (this.isSelected) {
                return 'lead'
            }
        }


    },
}
</script>

<style scoped>

</style>
