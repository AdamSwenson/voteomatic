<template>

    <section class="vote-receipt" role="status" aria-live="polite" tabindex="-1">
        <div class="receipt-icon" aria-hidden="true">✓</div>
        <div>
        <h2 class="receipt-title">Your vote has been recorded</h2>

        <p>Save this receipt now. You can use it later to verify that your vote was counted.</p>

        <code class="receipt-code user-select-all">{{ receipt }}</code>

        <p class="receipt-privacy">The receipt is not linked to you in the database.
            You are the only one who knows it belongs to you. If you lose it, it will be
            impossible to retrieve.</p>

        <p>
            <span class="me-2"><copy-receipts-button></copy-receipts-button></span>
            <download-receipts-button></download-receipts-button>
        </p>
        </div>

    </section>


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
.vote-receipt { display: flex; gap: 1rem; margin-top: 1.25rem; border: 1px solid #86efac; border-radius: .5rem; background: #f0fdf4; color: #14532d; padding: 1.25rem; }
.receipt-icon { display: grid; place-items: center; flex: none; width: 2rem; height: 2rem; border-radius: 50%; background: #15803d; color: white; font-weight: 700; }
.receipt-title { margin: 0 0 .5rem; font-size: 1.25rem; }.receipt-code { display: block; overflow-wrap: anywhere; margin: .75rem 0; border-radius: .25rem; background: white; color: #14532d; padding: .75rem; }.receipt-privacy { font-size: .9rem; }
</style>
