<template>

    <div class="motion-results-page ">

        <div class="card results-display" v-if="isMotionComplete">

            <div class="card">

                <div class="card-body" v-bind:class="resultStyle">
                    <h1 class="card-title ">The motion {{ passed }}</h1>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <blockquote>
                                <motion-text-display :motion="motion" :motion-style="motionStyle"></motion-text-display>
                            </blockquote>

                            <p class="text-lg-start text-sm-end ">

                            <span class="me-2">
                            <required-vote-badge :motion="motion"></required-vote-badge>
                            </span>

                            <span class="me-2">
                                <debatable-badge :motion="motion"></debatable-badge>
                            </span>
                            <motion-type-badge :motion="motion"></motion-type-badge>
                            </p>
                        </div>

                        <div class="col-lg-6">
                            <div class="card-text fw-bolder">
                                <dl class="row">
                                    <dt class="col-sm-3 ">Yays</dt>
                                    <dd class="col-sm-9">{{ yayCount }}</dd>
                                </dl>

                                <dl class="row">
                                    <dt class="col-sm-3">Nays</dt>
                                    <dd class="col-sm-9">{{ nayCount }}</dd>
                                </dl>

                                <dl class="row">
                                    <dt class="col-sm-3">Abstentions</dt>
                                    <dd class="col-sm-9">No such thing</dd>
                                </dl>

                                <dl class="row">
                                    <dt class="col-sm-3">Total votes cast</dt>
                                    <dd class="col-sm-9">{{ totalVotes }}</dd>
                                </dl>
                            </div>
                        </div>


                    </div>

                </div>

            </div>
        </div>

        <div class="card vote-in-progress" v-else>

            <div class="card-header ">
                <h4 class="card-title">Voting is still in progress.</h4>
            </div>

            <div class="card-body">

                <h5 class="card-title"> The results will be available once voting is
                    complete.</h5>

                <h5 class="card-subtitle">If you should be seeing results, please refresh your browser.</h5>
            </div>

        </div>

    </div>

</template>

<script>
import * as routes from "../../routes";
import Motion from '../../models/Motion';
import motionMixin from '../../mixins/motionStoreMixin';
import motionObjectMixin from "../../mixins/motionObjectMixin";
import MotionResultsMixin from '../../mixins/motionResultsMixin';

import RequiredVoteBadge from "../motions/badges/required-vote-badge";
import DebatableBadge from "../motions/badges/debatable-badge";
import MotionTypeBadge from "../motions/badges/motion-type-badge";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import MotionTextDisplay from "../motions/text-display/motion-text-display";

export default {
    name: "motion-results-page",
    components: {MotionTextDisplay, MotionTypeBadge, DebatableBadge, RequiredVoteBadge},
    mixins: [motionMixin, motionObjectMixin, MotionResultsMixin],

    data: function () {
        return {
            showCounts: false
        }
    },

    computed: {

        routeName: function () {
            return this.$route.name;
        },

        passed: function () {
            if (_.isUndefined(this.isPassed) || _.isNull(this.isPassed)) return ' ----- '

            return this.isPassed ? 'PASSED' : 'FAILED';

        },


        resultStyle: function () {
            //nb results will be a boolean
            if (!isReadyToRock(this.isPassed)) return '';
            if (this.isPassed) return "bg-success"
            return "bg-danger";
        },

        motionStyle: function(){
            if (! this.isResolution){
                return 'border border-1 p-4 fs-3'
            }

        },
    },

    methods: {

        loadResults: function () {
            let me = this;

            if (_.isUndefined(this.motion) || _.isNull(this.motion)) return false;
            console.log('Loading vote results', this.motion);

            me.$store.dispatch('loadMotionResults', me.motion).then(function () {

                //todo if want to block from getting vote totals put the break here

                window.console.log('Loading vote counts', me.motion);

                me.$store.dispatch('loadMotionCounts', me.motion).then(function () {
                    window.console.log('Results page ready', 189, me.motion);
                });
            });


            // me.$store.dispatch('loadResults', me.motion).then(function () {
            //
            //     //todo if want to block from getting vote totals put the break here
            //
            //     window.console.log('Loading vote counts', me.motion);
            //
            //     me.$store.dispatch('loadCounts', me.motion).then(function () {
            //         window.console.log('Results page ready', 189, me.motion);
            //     });
            // });

        }

    },


    watch: {
        // call again the method if the route changes
        '$route': 'loadResults',
        'motion': 'loadResults'
    },


    created() {
        // fetch the data when the view is created and the data is
        // already being observed
        this.loadResults()
    },

    mounted: function () {
        // this.loadResults();
        // this.$store.commit('setNavTrigger', false);
    }


}
</script>

<style scoped>

</style>
