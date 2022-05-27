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
            containerStyle: ' border border-warning  border-2 ',
            icon : 'bi-scissors',
            textStyling : "", // "text-decoration-line-through",
            iconStyling : "text-warning "
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
