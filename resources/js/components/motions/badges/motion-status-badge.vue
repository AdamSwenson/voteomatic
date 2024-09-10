<template>


        <span class="result-badge">
            <span class="no-results" v-if="! showBadge"></span>

             <span class="badge bg-success"
                   v-else-if="isPassed"
             >Passed</span>

            <span class="badge bg-danger"
                  v-else-if="! isPassed"
            >Failed</span>

        </span>

</template>

<script>
export default {
    name: "motion-status-badge",

    props: ["isPassed"],

    mixins: [],

    data: function () {
        return {}
    },

    computed: {},

    asyncComputed: {
        // isPassed: {
        //     get: function () {
        //         if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
        //             return this.$store.getters.getMotionPassed(this.motion);
        //         }
        //     },
        //     default: null
        // },

        showBadge: function () {
            //If the thing hasn't been voted on yet, isPassed will be undefined
            if (_.isUndefined(this.isPassed) || _.isNull(this.isPassed)) return false

            return true

        },

    }

}
</script>

<style scoped>

</style>
