<template>

    <div class="vote-receipt alert alert-success" role="alert">
        <h4 class="alert-heading">Your vote has been recorded</h4>

        <p>The fact that your vote was counted can be verified with this receipt:</p>

        <p><strong> {{ receipt }}</strong></p>

        <p>Please record this receipt. Once you leave this page, it will not be possible to
            retrieve it. </p>

        <hr>
        <p>The receipt is not linked to you in the database.
            You are the only one who knows it belongs to you. If you lose it, it will be
            impossible to retrieve.</p>


    </div>


</template>

<script>
export default {
    name: "vote-receipt",

    props: ['receipt']
}
</script>

<style scoped>

</style>
