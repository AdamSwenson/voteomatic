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
                                        <dd class="col-sm-9">{{yayCount}}</dd>
                                    </dl>

                                    <dl class="row">
                                        <dt class="col-sm-3">Nays</dt>
                                        <dd class="col-sm-9">{{nayCount}}</dd>
                                    </dl>

                                    <dl class="row">
                                        <dt class="col-sm-3">Abstentions</dt>
                                        <dd class="col-sm-9">No such thing</dd>
                                    </dl>

                                    <dl class="row">
                                        <dt class="col-sm-3">Total votes cast</dt>
                                        <dd class="col-sm-9">{{totalVotes}}</dd>
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

export default {
    name: "results-page",

    data: function () {
        return {
            showCounts: false
        }
    },

    computed: {
        motion: function () {
            let d = window.startData.motion;
            let m = new Motion(d.id, d.content, d.description, d.requires);
            return m;
        },

    },

    asyncComputed: {

        counts: function () {
            //try to get but don't complain if not allowed.

            let url = routes.results.getCounts(this.motion.id);

            return new Promise((resolve, reject) => {
                Vue.axios.get(url).then((response) => {
                    return resolve(response.data);
                });

            });
        },

        passed: function () {
            if (_.isUndefined(this.results) || _.isNull(this.results)) return ' ----- '

            return this.results.passed ? 'PASSED' : 'FAILED';

        },

        results: function () {

            let url = routes.results.getResults(this.motion.id);

            return new Promise((resolve, reject) => {
                Vue.axios.get(url).then((response) => {
                    return resolve(response.data);
                });

            });
        },

        yayCount : function(){
            if (_.isUndefined(this.counts) || _.isNull(this.counts)) return ' -- '
            // return 'dog';
            return this.counts.yayCount;

        },

        nayCount : function(){
            if (_.isUndefined(this.counts) || _.isNull(this.counts)) return ' -- '
            return this.counts.nayCount;
            // return 'dog';
        },

        totalVotes: function(){
            if (_.isUndefined(this.results) || _.isNull(this.results)) return ' -- '
// return 'dog';
            return this.results.totalVotes;
        }
    },

    methods: {
        handleClick: function () {
        },
        releaseResults: function () {
        }

    }

}
</script>

<style scoped>

</style>
