<!--<template>-->
<!--<span v-bind:class="containerStyling">-->
<!--     <span v-bind:class="iconStyling"-->
<!--     ><i class="bi "-->
<!--         v-bind:class="icon"-->
<!--     ></i></span>&nbsp;-->
<!--    <slot></slot>-->
<!--&lt;!&ndash;    <slot name="text"></slot>&ndash;&gt;-->
<!--&lt;!&ndash;    <span v-bind:class="textStyling" v-html="text"></span>&ndash;&gt;-->
<!--</span>-->
<!--</template>-->

<script>
import TextStylerParentRegular from "./text-styler-parent-regular";
export default {
    name: "insert-pending",

    extends : TextStylerParentRegular,
    // extends : ComponentCompilerParent,
    // props: [
    //     // 'text',
    //     'amendmentId'],

    mixins: [],

    data: function () {
        return {
            containerStyle: ' border border-primary border-3 fs-2',
            icon: 'bi-pencil',
            textStyling: "text-decoration-underline ",
            iconStyling: "text-primary"
        }
    },

    asyncComputed: {},

    computed: {
        // containerStyling: function () {
        //     return this.name + this.containerStyle;
        // }
    },

    methods: {}

}
</script>

<style scoped>

</style>
