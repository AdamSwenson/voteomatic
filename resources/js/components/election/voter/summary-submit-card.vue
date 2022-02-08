<template>
<div class="summary-submit-card card">

    <summary-listing :motion="m" v-for="m in motions" :key="m.id"></summary-listing>


</div>
</template>

<script>

import SummaryListing from "./summary-listing";
/**
 * This is where the votes will be reviewed and submitted
 */
export default {
    name: "summary-submit-card",
    components: {SummaryListing},
    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        motions: function(){
            return this.$store.getters.getMotions;
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
