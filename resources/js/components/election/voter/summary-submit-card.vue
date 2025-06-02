<template>
    <div class="summary-submit-card card">
        <div class="card-body instructions">
            <p class="card-text">Please review your selections.</p>
            <p  class="card-text"> When you are ready, please
        click 'Record all selections' to save your votes. </p>

            <p class="card-text">Once your selections have been saved, they cannot be changed.</p>

            <p class="card-text">If you have not selected any candidates for an office,
                you may return and finish voting. However, if you have selected less than the maximum allowed number of candidates, you will not be able
                to return and select additional candidates. </p>

            <p class="card-text">To change a selection, click the office name in the list to the left.</p>

        </div>

        <summary-office-listing :motion="m" v-for="m in offices" :key="m.id"></summary-office-listing>

        <summary-proposition-listing :motion="m" v-for="m in propositions" :key="m.id"></summary-proposition-listing>

        <div class="card-body" >
            <div class="d-grid gap-2">
            <record-all-selections-button></record-all-selections-button>
            <record-all-selections-modal></record-all-selections-modal>
        </div>
        </div>

<!--        <navigation-footer></navigation-footer>-->

    </div>
</template>

<script>

import SummaryListing from "../voting/summary-submit/summary-office-listing";
import RecordAllSelectionsButton from "../voting/summary-submit/record-all-selections-button";
import RecordAllSelectionsModal from "../voting/summary-submit/record-all-selections-modal";
import CopyReceiptsButton from "../../vote-verification/copy-receipts-button";
import DownloadReceiptsButton from "../../vote-verification/download-receipts-button";
import ReceiptListArea from "../../vote-verification/receipt-list-area";
import NavigationFooter from "./navigation/navigation-footer";
import SummaryOfficeListing from "../voting/summary-submit/summary-office-listing";
import SummaryPropositionListing from "../voting/summary-submit/summary-proposition-listing";

/**
 * This is where the votes will be reviewed and submitted
 */
export default {
    name: "summary-submit-card",
    components: {
        SummaryPropositionListing,
        SummaryOfficeListing,
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
        // motions: function () {
        //     return this.$store.getters.getMotions;
        // },

        offices: function(){
            return this.$store.getters.getElectionOffices;
        },
        propositions : function(){
            return this.$store.getters.getElectionPropositions;
        }

    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
