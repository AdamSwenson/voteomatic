<!--<template>-->
<!--<span v-bind:class="containerStyling">-->
<!--     <i class="bi " v-bind:class="icon"></i>&nbsp;<span v-bind:class="textStyling" v-html="text"></span>-->
<!--</span>-->
<!--</template>-->

<script>
import TextStylerParentRegular from "./text-styler-parent-regular";
export default {
    name: "strike-pending-superseded",
    extends: TextStylerParentRegular,
    // props: ['text', 'amendmentId'],

    // mixins: [],


    data: function () {
        return {
            containerStyle: ' border border-dark  border-3 fs-1 ',
            icon : 'bi-scissors',
            textStyling : "text-decoration-line-through",
            iconStyling : " "
        }
    },

    // asyncComputed: {},
    //
    // computed: {
    //     containerStyling: function () {
    //         return this.name + this.containerStyle;
    //     }
    // },
    //
    // methods: {}

}
</script>

<style scoped>

</style>
