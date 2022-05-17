<template>
<span v-bind:class="containerStyling">
     <span v-bind:class="iconStyling"
     ><i class="bi "
         v-bind:class="icon"
     ></i></span>&nbsp;<slot></slot>
    <!--    <slot name="text"></slot>-->
    <!--    <span v-bind:class="textStyling" v-html="text"></span>-->
</span>
</template>

<script>
import ComponentCompilerParent from "../component-compiler-parent";

export default {
    name: "text-styler-parent-regular",

    // extends : ComponentCompilerParent,
    props: [
        // 'text',
        'amendmentId'],

    mixins: [],

    data: function () {
        return {
            // containerStyle: ' border border-primary border-3 fs-1',
            // icon: 'bi-pencil',
            // textStyling: "text-decoration-underline ",
            // iconStyling: "text-primary"
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
