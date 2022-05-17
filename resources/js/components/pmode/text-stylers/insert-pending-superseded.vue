<template>
<span v-bind:class="containerStyling">
      <i class="bi "
         v-bind:class="icon"
      ></i>&nbsp;<slot></slot>
<!--    <span v-bind:class="textStyling" v-html="text"></span>-->
</span>
</template>

<script>
export default {
    name: "insert-pending-superseded",

    props: [
        // 'text',
        'amendmentId'],

    mixins: [],

    data: function () {
        return {
            containerStyle: ' border border-warning border-3 ',
            textStyling: "text-decoration-underline ",
            icon: 'bi-pencil',
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
