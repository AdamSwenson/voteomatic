<template>

    <div class="motion-results">

        <div class="card results-display" v-if="isMotionComplete">

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
import motionMixin from '../../mixins/motionMixin';

export default {
    name: "results-page",

    mixins: [motionMixin],

    data: function () {
        return {
            showCounts: false
        }
    },

    computed: {


        routeName: function () {
            return this.$route.name;
        }
    },

    asyncComputed: {

        passed: function () {
            let results = this.$store.getters.getPassed;
            if (_.isUndefined(results) || _.isNull(results)) return ' ----- '

            return results ? 'PASSED' : 'FAILED';

        },


        yayCount: function () {
            return this.$store.getters.getYayCount
        },

        nayCount: function () {
            return this.$store.getters.getNayCount
        },

        totalVotes: function () {
            return this.$store.getters.getTotalVoteCount;
        }
    },

    methods: {

        loadResults: function () {
            let me = this;

            if (_.isUndefined(this.motion) || _.isNull(this.motion)) return false;
            console.log('Loading vote results', this.motion);

            me.$store.dispatch('loadResults', me.motion).then(function () {

                //todo if want to block from getting vote totals put the break here

                window.console.log('Loading vote counts', me.motion);

                me.$store.dispatch('loadCounts', me.motion).then(function () {
                    window.console.log('Results page ready', 189, me.motion);
                });
            });

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
        this.loadResults();
    }


}
</script>

<style scoped>

</style>
