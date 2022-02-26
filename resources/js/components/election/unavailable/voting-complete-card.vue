<template>
    <div class="election-complete-card card" >
        <div class="card-header">
            <h3 class="card-title">Thank you for voting!</h3>
        </div>

        <div class="card-body">
            <p class="card-text">If you did not vote for every position, you can finish voting by refreshing the page or logging back in from Canvas later (make sure you have recorded your receipts first!) </p></div>
<!--        on the <a href="#" v-on:click="handleNavigationToVote">Home tab</a>. </p> </div>-->

        <div class="card-body" v-if="showReceipts">
            <p class="card-text">The receipts below are temporarily stored on your browser. If you refresh the page,
                it will no longer be possible to retrieve your receipts since your user id is not
                linked to them in the database. Use the buttons below to download a list of your
                receipts for safekeeping</p>

            <p class="card-text">Please feel free to use the <a href="#" v-on:click="handleNavigationToVerify">Verify votes tab</a> to confirm the validity of your receipts</p>

            <receipt-list-area></receipt-list-area>
        </div>

        <div class="card-footer" v-if="showReceipts">
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
import AllReceiptsMixin from "../../../mixins/allReceiptsMixin";
export default {
    name: "voting-complete-card",
    components: {DownloadReceiptsButton, CopyReceiptsButton, ReceiptListArea, ReceiptListItem},
    props: [],

    mixins: [AllReceiptsMixin,],

    data: function () {
        return {}
    },

    asyncComputed: {
        showReceipts : function(){
            return this.$store.getters.getUsersCastVotes.length > 0;
        }
    },

    computed: {},

    methods: {

        handleNavigationToVerify : function(){
            this.$router.push('verify');
        },

        handleNavigationToVote : function(){
            this.$router.push('election-home');
        }
    }

}
</script>

<style scoped>

</style>
