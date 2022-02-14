<template>
    <div class="election-complete-card card" >
        <div class="card-body">
            <p class="card-text font-weight-bold">Thank you for voting!</p>
        </div>

        <div class="card-body" v-if="showReceipts">
            <p class="card-text">The receipts below are temporarily stored on your browser. If you refresh the page,
                it will no longer be possible to retrieve your receipts since your user id is not
                linked to them in the database. Use the buttons below to download a list of your
                receipts for safekeeping</p>

            <p class="card-text">Please feel free to use the 'Verify votes' tab to confirm the validity of your receipts</p>

            <receipt-list-area></receipt-list-area>
        </div>

        <div class="card-footer">
            <copy-receipts-button></copy-receipts-button>
            <download-receipts-button></download-receipts-button>
        </div>

    </div>
</template>

<script>
import ReceiptListItem from "../../vote-verification/receipt-list-item";
import ReceiptListArea from "../../vote-verification/receipt-list-area";
import CopyReceiptsButton from "../../vote-verification/copy-receipts-button";
import DownloadReceiptsButton from "../../vote-verification/download-receipts-button";
export default {
    name: "voting-complete-card",
    components: {DownloadReceiptsButton, CopyReceiptsButton, ReceiptListArea, ReceiptListItem},
    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        showReceipts : function(){
            return this.$store.getters.getUsersCastVotes.length > 0;
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
