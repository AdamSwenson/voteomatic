<!--<template>-->
<!--<span v-bind:class="containerStyling">-->
<!--     <span v-bind:class="iconStyling"-->
<!--     ><i class="bi "-->
<!--        v-bind:class="icon"-->
<!--     ></i></span>&nbsp;<span v-bind:class="textStyling" v-html="text"></span>-->
<!--</span>-->

<!--</template>-->

<script>
import TextStylerParentRegular from "./text-styler-parent-regular";

export default {
    name: "strike-pending",
    extends: TextStylerParentRegular,
    // props: ['text', 'amendmentId'],

    // mixins: [],

    data: function () {
        return {
            containerStyle: ' border border-primary  border-3 fs-2',
            icon: 'bi-scissors',
            textStyling: " text-decoration-line-through ",
            iconStyling: "text-primary"
        }
    },
    //
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
