<template>
    <div class="p-mode-chair-controls">
        Set as current rezzie (in stack, not just on client)
        <motion-select-button :motion="motion"></motion-select-button>


        <mark-passed-button :motion="motion"></mark-passed-button>

        <mark-failed-button :motion="motion"></mark-failed-button>

    </div>
</template>

<script>
import MotionSelectButton from "../motions/motion-select-button";
import MarkPassedButton from "./controls/mark-passed-button";
import MarkFailedButton from "./controls/mark-failed-button";

export default {
    name: "p-mode-chair-controls",
    components: {MarkFailedButton, MarkPassedButton, MotionSelectButton},
    props: ['motion'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
