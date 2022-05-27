<template>

    <button
        class="btn "
        type="button"
        v-bind:class="styling"
        v-on:click="handleClick"
    >
        <span v-if="isWorking" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        <span v-if="isWorking" class="visually-hidden">Working...</span> {{label}}</button>

</template>

<script>
import {isReadyToRock} from "../../utilities/readiness.utilities";

/**
 * Common parent for all buttons
 */
export default {
    name: "button-parent",


    mixins: [],

    data: function () {
        return {
            // label:
            // styling:
            //isWorking : false
        }
    },

    asyncComputed: {

    },

    computed: {
        isWorking : function (){
            return false;
        }
    },

    methods: {

        handleClick: function () {

        }
    }

}
</script>

<style scoped>

</style>
