<template>

    <button
        class="btn"
        v-bind:class="styling"
        v-on:click="handleClick"
    >{{label}}</button>

</template>

<script>
import {isReadyToRock} from "../../utilities/readiness.utilities";

/**
 * Common parent for all buttons
 */
export default {
    name: "button-parent",

    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {

    },

    computed: {},

    methods: {

        handleClick: function () {

        }
    }

}
</script>

<style scoped>

</style>
