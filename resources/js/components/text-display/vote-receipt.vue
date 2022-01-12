<template>

    <div class="vote-receipt alert alert-success" role="alert">
        <h4 class="alert-heading">Your vote has been recorded</h4>

        <p>The fact that your vote was counted can be verified with this receipt:</p>

        <p class="user-select-all"><strong> {{ receipt }}</strong></p>

        <hr>
        <p>The receipt is not linked to you in the database.
            You are the only one who knows it belongs to you. If you lose it, it will be
            impossible to retrieve.</p>

        <p>
            <copy-receipts-button></copy-receipts-button>
            <download-receipts-button></download-receipts-button>
        </p>


    </div>


</template>

<script>
import DownloadReceiptsButton from "../vote-verification/download-receipts-button";
import CopyReceiptsButton from "../vote-verification/copy-receipts-button";
export default {
    name: "vote-receipt",
    components: {CopyReceiptsButton, DownloadReceiptsButton},
    props: ['receipt']
}
</script>

<style scoped>

</style>
