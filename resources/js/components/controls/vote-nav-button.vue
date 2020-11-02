<template>

    <button class="btn btn-primary"
    v-on:click="handleClick"
    >Vote</button>

</template>

<script>
export default {
    name: "vote-nav-button",

    props: ['motion'],

    mixins: [],

    data: function () {
        return {}
    },

    computed: {},

    methods: {
        handleClick: function () {
this.$router.push('vote');
        }

    }
}
</script>

<style scoped>

</style>
