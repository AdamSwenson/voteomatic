
<template>

    <button class="btn btn-warning btn-lg btn-block"
            v-on:click="handleClick"
    >Results</button>

</template>

<script>
export default {
    name: "results-nav-button",

    props: ['motion'],

    mixins: [],

    data: function () {
        return {}
    },

    computed: {},

    methods: {
        handleClick: function () {
            this.$router.push('results');
        }

    }
}
</script>

<style scoped>

</style>
