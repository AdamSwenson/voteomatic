<template>
    <div class="summary-submit-card card">

        <summary-listing :motion="m" v-for="m in motions" :key="m.id"></summary-listing>

        <div class="card-body" >
            <record-all-selections-button></record-all-selections-button>
            <record-all-selections-modal></record-all-selections-modal>
        </div>

        <navigation-footer></navigation-footer>

    </div>
</template>

<script>

import SummaryListing from "../voting/summary-listing";
import RecordAllSelectionsButton from "../voting/record-all-selections-button";
import RecordAllSelectionsModal from "../voting/record-all-selections-modal";
import CopyReceiptsButton from "../../vote-verification/copy-receipts-button";
import DownloadReceiptsButton from "../../vote-verification/download-receipts-button";
import ReceiptListArea from "../../vote-verification/receipt-list-area";
import NavigationFooter from "./navigation/navigation-footer";

/**
 * This is where the votes will be reviewed and submitted
 */
export default {
    name: "summary-submit-card",
    components: {
        NavigationFooter,
        ReceiptListArea,
        DownloadReceiptsButton,
        CopyReceiptsButton, RecordAllSelectionsModal, RecordAllSelectionsButton, SummaryListing
    },
    props: [],

    mixins: [],


    data: function () {
        return {}
    },

    asyncComputed: {
        motions: function () {
            return this.$store.getters.getMotions;
        },

    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
