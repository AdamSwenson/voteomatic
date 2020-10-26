<template>

    <div class="vote-verification">
        <h5>Please enter your receipt in the box to confirm that the vote has been counted.</h5>
        <input type="text" id="receipt-entry" v-model="receipt">
        <p>
            <button type="button" class="btn btn-info"
                    v-on:click="handleClick"
            >Verify vote
            </button>
        </p>

        <p v-if="verificationResult"></p>

        <p>Feel free to enter fake receipts to check the legitimacy of your receipt</p>
    </div>
</template>

<script>
export default {
    name: "vote-verification-page",
    data: function () {
        return {
            receipt: ''

        }
    },
    computed: {

        verificationResult: function(){
            return false;

            //return "Vote for {{voteType}} was received {{timestamp}}"
        }

    },

    methods: {
        verifyReceipt: function () {

        },

        handleClick: function(){
            alert('something will happen');
        }
    }

}
</script>

<style scoped>

</style>
