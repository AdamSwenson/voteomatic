<template>
    <div class="summary-submit-card card">

        <summary-listing :motion="m" v-for="m in motions" :key="m.id"></summary-listing>

        <div class="card-footer">
            <record-all-selections-button></record-all-selections-button>
            <record-all-selections-modal></record-all-selections-modal>
        </div>

    </div>
</template>

<script>

import SummaryListing from "./summary-listing";
import RecordAllSelectionsButton from "../voting/record-all-selections-button";
import RecordAllSelectionsModal from "../voting/record-all-selections-modal";

/**
 * This is where the votes will be reviewed and submitted
 */
export default {
    name: "summary-submit-card",
    components: {RecordAllSelectionsModal, RecordAllSelectionsButton, SummaryListing},
    props: [],

    mixins: [],




    data: function () {
        return {}
    },

    asyncComputed: {
        motions: function () {
            return this.$store.getters.getMotions;
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
