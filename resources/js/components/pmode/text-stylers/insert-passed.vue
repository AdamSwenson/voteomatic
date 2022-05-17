<!--<template>-->
<!--    <span class="border border-success insert-passed">-->
<!--        <i class="bi bi-pencil"></i>&nbsp;<span v-html="text"></span>-->
<!--    </span>-->
<!--</template>-->

<script>

import TextStylerParentRegular from "./text-styler-parent-regular";

/**
 * Indicates that the provided text was successfully inserted
 * by a previous motion.
 */
export default {
    name: "insert-passed",
    extends : TextStylerParentRegular,
    // extends : ComponentCompilerParent,
    // props: [
    //     // 'text',
    //     'amendmentId'],

    mixins: [],

    data: function () {
        return {
            containerStyle: ' border border-success ',
            icon: 'bi-pencil',
            textStyling: "text-decoration-underline ",
            iconStyling: "text-success"
        }
    },
    // props: [
    //     // 'text',
    //     'amendmentId'],
    //
    // mixins: [],
    //
    // data: function () {
    //     return {}
    // },
    //
    // asyncComputed: {},
    //
    // computed: {},
    //
    // methods: {}

}
</script>

<style scoped>

</style>
