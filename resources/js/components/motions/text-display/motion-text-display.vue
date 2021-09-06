<template>

    <div class="motion-text-display " v-bind:class="styling">

        <component
            v-bind:is="displayComponent"
            :motion="motion"
        ></component>


    </div>


</template>

<script>
import MotionMixin from "../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../mixins/meetingMixin";
import motionObjectMixin from "../../../mixins/motionObjectMixin";
import AmendmentMixin from "../../../mixins/amendmentMixin";
import ResolutionTextDisplay from "./resolution-text-display";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import PlainTextPrimaryMotionTextDisplay from "./plain-text-primary-motion-text-display";
import AmendmentTextDisplay from "./amendment-text-display";
import RegularAmendmentTextDisplay from "./regular-amendment-text-display";
import ResolutionAmendmentTextDisplay from "./resolution-amendment-text-display";

/**
 * Decides how the format the content of a motion.
 *
 * THIS IS THE PRIMARY MOTION TEXT DISPLAY THAT SHOULD BE USED
 * ALMOST EVERYWHERE
 */
export default {
    name: "motion-text-display",
    components: {
        ResolutionAmendmentTextDisplay,
        RegularAmendmentTextDisplay,
        AmendmentTextDisplay, PlainTextPrimaryMotionTextDisplay, ResolutionTextDisplay
    },
    props: [
        'motion',
        /** Since this is used in different contexts, the styling gets passed in from the parent */
        'motionStyle'
    ],

    mixins: [motionObjectMixin, AmendmentMixin],

    data: function () {
        return {}
    },

    asyncComputed: {

        displayComponent: function () {
            if (isReadyToRock(this.motion)) {

                switch (this.motion) {
                    case this.isResolution && ! this.isAmendment:
                        //Motion is primary resolution
                        return ResolutionTextDisplay;
                        break;

                    case this.isResolution && this.isAmendment:
                        //Motion is an amendment to a resolution
                        return ResolutionAmendmentTextDisplay;
                        break;

                    case this.isAmendment:
                        //Motion is an amendment to a non-resolution motion
                        return RegularAmendmentTextDisplay;
                        break;

                    default:
                        return PlainTextPrimaryMotionTextDisplay;

                }


            }
        }

    },

    computed: {
        styling: function () {
            if (isReadyToRock(this.motionStyle)) {
                return this.motionStyle
            }
        }
    },

    methods: {}

}
</script>

<style scoped>

</style>
