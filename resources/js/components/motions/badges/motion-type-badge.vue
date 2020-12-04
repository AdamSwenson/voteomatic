<template>

    <span
        class="badge "
        v-bind:class="styling"
    >{{ labelText }}</span>

</template>

<script>
import MotionObjectMixin from "../../../mixins/motionObjectMixin";
import ProceduralMixin from "../../../mixins/proceduralMixin";

export default {
    name: "motion-type-badge",

    props: ["motion"],

    mixins: [MotionObjectMixin, ProceduralMixin],

    data: function () {
        return {

            procedural: {
                styling: 'badge-secondary',
                text: 'Procedural'
            },

            amendment: {
                styling: 'badge-warning',
                text: 'Amendment'
            }

        }
    },

    asyncComputed: {

        styling: function () {
            //stupid async
            if (this.isMotionReady) {
                if (this.isAmendment) return this.amendment.styling;

                if (this.isProcedural) return this.procedural.styling;

            }
        },

        labelText: function () {
            //stupid async
            if (this.isMotionReady) {
                if (this.isAmendment) return this.amendment.text;

                if (this.isProcedural) return this.procedural.text;

            }
        },

    }

}
</script>

<style scoped>

</style>
