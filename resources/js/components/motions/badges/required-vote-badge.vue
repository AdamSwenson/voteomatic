<template>

    <span
        class="badge "
        v-bind:class="badgeStyling"
    >{{ labelText }}</span>

</template>

<script>
import MotionObjectMixin from "../../../mixins/motionObjectMixin";
import ProceduralMixin from "../../../mixins/proceduralMixin";

export default {
    name: "required-vote-badge",

    props: ["motion"],

    mixins: [MotionObjectMixin],

    data: function () {
        return {

            majority: {
                styling: 'bg-primary',
                text: 'Majority',
                tip: 'A majority means greater than 50% of all votes cast.'
            },
            twoThirds: {
                styling: 'bg-warning',
                text: 'Two-thirds',
                tip: 'This requires greater than 2/3 of all votes cast.'
            }

        }
    },

    asyncComputed: {

        requirement: function () {

            if (this.isMotionReady) {
                switch (this.motion.requires) {
                    case 0.5:
                        return this.majority;
                        break;
                    case 0.66:
                        return this.twoThirds;
                        break;
                }
            }
        },

        badgeStyling: {
            get: function () {
                //stupid async
                if (this.isMotionReady && !_.isUndefined(this.requirement)) {
                    return this.requirement.styling;
                }
            },
            default: ''

        },

        labelText: {
            get: function () {

                if (this.isMotionReady && !_.isUndefined(this.requirement)) {
                    return this.requirement.text;
                }
            },
            default: ''
        }

    }

}
</script>

<style scoped>

</style>
