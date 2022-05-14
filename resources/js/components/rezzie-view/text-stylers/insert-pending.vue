<template>
<span v-bind:class="containerStyling">
     <i class="bi "
        v-bind:class="icon"
     ></i> <span v-bind:class="textStyling" v-html="text"></span>
</span>
</template>

<script>
export default {
    name: "insert-pending",

    props: ['text', 'amendmentId'],

    mixins: [],

    data: function () {
        return {
            containerStyle: ' border border-primary border-3 fs-1',
            icon: 'bi-pencil',
            textStyling: "text-decoration-underline "
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
