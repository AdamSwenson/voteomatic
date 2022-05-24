<template>
<div class="resolved-clause "
v-bind:styling="styling">

</div>
</template>

<script>
export default {
    name: "resolved-clause",

    props: ['motion', 'text'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        styling: function(){}
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
