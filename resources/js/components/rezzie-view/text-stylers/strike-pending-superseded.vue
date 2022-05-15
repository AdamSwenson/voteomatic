<template>
<span v-bind:class="containerStyling">
     <i class="bi " v-bind:class="icon"></i>&nbsp;<span v-bind:class="textStyling" v-html="text"></span>
</span>
</template>

<script>
export default {
    name: "strike-pending-superseded",

    props: ['text', 'amendmentId'],

    mixins: [],

    data: function () {
        return {
            containerStyle: ' border border-dark  border-3 fs-1 ',
            icon : 'bi-scissors',
            textStyling : "text-decoration-line-through"
        }
    },

    asyncComputed: {},

    computed: {
        containerStyling: function () {
            return this.name + this.containerStyle;
        }
    },

    methods: {}

}
</script>

<style scoped>

</style>
