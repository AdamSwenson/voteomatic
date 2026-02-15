<template>
    <div class="invalid-receipt-alert alert alert-danger" role="alert">

        <h4 class="alert-heading">This is not a valid receipt</h4>

        <p>The database does not contain a record of this vote.</p>

        <p>If you voted before voting was restarted,
            the receipt for that vote will be invalid because all records were removed.</p>

        <p> Receipt : {{ receipt }} </p>

        <p class="text-end">
            <button type="button" class="btn btn-info" v-on:click="closeAlert">Close</button>
        </p>

    </div>

</template>

<script>

export default {
    name: "invalid-receipt-alert",

    components: {},

    props: ['receipt'],

    mixins: [],

    data: function () {
        return {}
    },

    computed: {},

    methods: {
        closeAlert: function () {
            this.$emit('closeAlert');
        }
    }
}

</script>

<style scoped>

</style>
