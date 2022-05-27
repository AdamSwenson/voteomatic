
<template>
    <div class="d-grid gap-2">
    <button class="btn btn-warning btn-lg "
            v-on:click="handleClick"
    >Results</button>
    </div>
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
