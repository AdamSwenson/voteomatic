<!--<template>-->
<!--    <span-->
<!--          class="strike-passed text-success"-->
<!--    >-->
<!--        <a-->
<!--            tabindex="0"-->
<!--            data-bs-toggle="popover"-->
<!--            data-bs-trigger="focus"-->
<!--            v-bind:data-bs-content="popoverContent"-->
<!--        ><i class="bi " v-bind:class="icon"></i></a>-->
<!--        </span>-->
<!--&lt;!&ndash;    <text-styler-parent-popover></text-styler-parent-popover>&ndash;&gt;-->
<!--</template>-->

<script>
import TextStylerParentPopover from "./text-styler-parent-popover";
/**
 * Indicates that text was successfully struck from this location
 */
export default {
    name: "strike-passed",

    extends: TextStylerParentPopover,
    // props: ['text', 'amendmentId'],

    // mixins: [],

    data: function () {
        return {
            textStyling: "",
            iconStyling: "text-success ",
            icon: 'bi-scissors',
            stylerName : 'strikePassed',
            tt: '  '
        }
    },
    //
    // asyncComputed: {
    //     popoverContent: function () {
    //         return this.text;
    //     },
    //     itemId : function () {
    //         return 'strikePassedAmendment' + this.amendmentId;
    //     }
    // },
    //
    // computed: {
    //     // containerStyling: function () {
    //     //     return this.name + this.containerStyle;
    //     // }
    // },
    //
    // methods: {},


}
</script>

<style scoped>

</style>
