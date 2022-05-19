<template>
    <div class="p-mode-chair-controls">
        <div class="row">
            <div class="col-md-6">
                <motion-select-button :motion="motion"></motion-select-button>
                <p class="form-text">Sets as current rezzie (in stack, not just on client)</p>
            </div>

            <div class="col-md-6">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">

                    <mark-passed-button v-if="! motion.isComplete" :motion="motion"></mark-passed-button>

                    <mark-failed-button v-if="! motion.isComplete" :motion="motion"></mark-failed-button>
                </div>
            </div>
        </div>
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
