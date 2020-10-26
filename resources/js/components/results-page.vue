<template>

    <div class="motion-results">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ motion.content }}</h5>

                <h6 class="card-subtitle mb-2 text-muted">This motion required {{ motion.englishRequires }}</h6>

                <h1 class="card-text">The motion {{ passed }}</h1>
            </div>

            <div class="card-body">
                <div class="card-text">
                    <dl class="row">
                        <dt class="col-sm-3">Yays</dt>
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

            <div class="card-footer text-muted">

                <p>Only if they are authorized to see this......</p>

                <button type="button" class="btn btn-warning"
                        v-on:click="handleClick"
                >Release results
                </button>

            </div>
        </div>


    </div>


</template>

<script>
import * as routes from "../routes";
import Motion from '../models/Motion';
import motionMixin from './storeMixins/motionMixin';

export default {
    name: "results-page",

    mixins: [motionMixin],

    data: function () {
        return {
            showCounts: false
        }
    },

    computed: {
        // motion: function () {
        //     let d = window.startData.motion;
        //     let m = new Motion(d);
        //
        //     // let m = new Motion(d.id, d.content, d.description, d.requires);
        //     return m;
        // },

    },

    asyncComputed: {

        // counts: function () {
        //
        //
        //     if (_.isUndefined(this.motion) || _.isNull(this.motion)) return 100000000000;
        //
        //     //try to get but don't complain if not allowed.
        //
        //     let url = routes.results.getCounts(this.motion.id);
        //
        //     return new Promise((resolve, reject) => {
        //         Vue.axios.get(url).then((response) => {
        //             return resolve(response.data);
        //         });
        //
        //     });
        // },

        passed: function () {
            return this.$store.getters.getPassed;

            // if (_.isUndefined(this.results) || _.isNull(this.results)) return ' ----- '

            // return this.results.passed ? 'PASSED' : 'FAILED';

        },

        // results: function () {
        //     if (_.isUndefined(this.motion)) return 100000000000;
        //
        //     let url = routes.results.getResults(this.motion.id);
        //
        //     return new Promise((resolve, reject) => {
        //         Vue.axios.get(url).then((response) => {
        //             return resolve(response.data);
        //         });
        //
        //     });
        // },

        yayCount: function () {
            return this.$store.getters.getYayCount

            // if (_.isUndefined(this.counts) || _.isNull(this.counts)) return ' -- '
            // return 'dog';
            // return this.counts.yayCount;

        },

        nayCount: function () {
            return this.$store.getters.getNayCount
            // if (_.isUndefined(this.counts) || _.isNull(this.counts)) return ' -- '
            // return this.counts.nayCount;
            // return 'dog';
        },

        totalVotes: function () {
            return this.$store.getters.getTotalVoteCount;

            // if (_.isUndefined(this.results) || _.isNull(this.results)) return ' -- '
// return 'dog';
//             return this.results.totalVotes;
        }
    },

    methods: {
        handleClick: function () {
        },
        releaseResults: function () {
        }

    },
    mounted: function () {
        let me = this;
        //parse data from page and store stuff
        this.$store.dispatch('initialize')
            .then(function () {
                console.log(me.motion, 'motion')
                //motion should be loaded now
                me.$store.dispatch('loadResults', me.motion).then(function () {

                        //todo if want to block from getting vote totals put the break here

                        me.$store.dispatch('loadCounts', me.motion).then(function () {
                                window.console.log('waggleback', 'isReady', 159, me.isReady);
                            });
                    });
            });
    }


}
</script>

<style scoped>

</style>
