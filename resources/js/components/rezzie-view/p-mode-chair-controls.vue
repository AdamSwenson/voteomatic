<template>
    <div class="p-mode-chair-controls">
        Set as current rezzie (in stack, not just on client)
        <motion-select-button :motion="motion"></motion-select-button>

        <mark-passed-button v-if="! motion.isComplete" :motion="motion"></mark-passed-button>

        <mark-failed-button  v-if="! motion.isComplete" :motion="motion"></mark-failed-button>

    </div>
</template>

<script>
import MotionSelectButton from "../motions/motion-select-button";
import MarkPassedButton from "./controls/mark-passed-button";
import MarkFailedButton from "./controls/mark-failed-button";
import ChairMixin from "../../mixins/chairMixin";

export default {
    name: "p-mode-chair-controls",
    components: {MarkFailedButton, MarkPassedButton, MotionSelectButton},
    props: ['motion'],

    mixins: [ChairMixin],

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
