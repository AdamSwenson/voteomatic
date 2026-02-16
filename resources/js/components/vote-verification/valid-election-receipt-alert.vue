<template>
    <div class="valid-election-receipt-alert alert alert-success"
         role="alert" >
        <h4 class="alert-heading">This receipt is valid</h4>
        <h5 class="alert-heading">The database contains a record of this vote</h5>
        <div class="ms-5 mt-3" v-if="showVoteContent">
            <h4 class="h4">{{ officeName }}</h4>
            <ul>
                <li>{{ candidateName }}</li>
            </ul>
        </div>

        <p>Receipt : <span class="user-select-all">{{ receipt }}</span></p>

        <p class="text-end">
            <button type="button" class="btn btn-info" v-on:click="closeAlert">Close</button>
        </p>
    </div>
</template>

<script>

/**
 * Created: 2/15/26
 */
export default {
    name: "valid-election-receipt-alert",

    components: {},

    props: ["receipt", "showVoteContent", "officeName", "candidateName", ],

    mixins: [],

    data: function () {
        return {}
    },

    computed: {

    },

    methods: {
        closeAlert: function () {
            this.$emit('closeAlert');
        }
    }
}

</script>

<style scoped>

</style>
