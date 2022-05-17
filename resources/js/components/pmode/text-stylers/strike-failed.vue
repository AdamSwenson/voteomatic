<!--<template>-->
<!--  <span class="border border-danger strike-failed">-->
<!--       <span class="text-danger">-->
<!--           <i class="bi bi-scissors"></i>-->
<!--       </span>&nbsp;<span v-html="text"></span>-->

<!--&lt;!&ndash;        <a v-bind:href="resultsLink" class="text-decoration-none">&ndash;&gt;-->
<!--&lt;!&ndash;            <span v-amendmentText="text"></span>&ndash;&gt;-->
<!--&lt;!&ndash;        </a>&ndash;&gt;-->
<!--  </span>-->
<!--</template>-->

<script>

import TextStylerParentRegular from "./text-styler-parent-regular";
/**
 * Indicates that there was an attempt to strike this text which failed.
 */
export default {

    name: "strike-failed",
    extends: TextStylerParentRegular,

    // props: ['text', 'amendmentId'],
    //
    // mixins: [],

    data: function () {
        return {
            containerStyle: ' border border-danger strike-failed ',
            icon: 'bi-scissors',
            textStyling: " ",
            iconStyling: "text-danger"
        }
    },
    //
    // asyncComputed: {},
    //
    // computed: {
    //     resultsLink: function () {
    //         //returns a link that will show results for the amendmentId
    //     }
    // },
    //
    // methods: {}

}
</script>

<style scoped>

</style>
