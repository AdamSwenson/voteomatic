<template>
    <div class="valid-motion-receipt-alert alert alert-success"
         role="alert" >
        <h4 class="alert-heading">This receipt is valid</h4>
        <h5 class="alert-heading">The database contains a record of this vote</h5>

        <p v-if="showVoteContent">The vote associated with this receipt is: <strong>{{ voteDisplay }}</strong>
        </p>

        <p>Receipt : <span class="user-select-all">{{ receipt }}</span></p>

        <p class="text-end">
            <button type="button" class="btn btn-info" v-on:click="closeAlert">Close</button>
        </p>
    </div>
</template>

<script>

import {isReadyToRock} from "../../utilities/readiness.utilities";

/**
 * Created: 2/15/26
 */
export default {
    name: "valid-motion-receipt-alert",

    components: {},

    props: ["receipt", "voteDisplay" ],

    mixins: [],

    data: function () {
        return {}
    },

    computed: {

        showVoteContent: function () {
            return isReadyToRock(this.voteDisplay);
        }

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
