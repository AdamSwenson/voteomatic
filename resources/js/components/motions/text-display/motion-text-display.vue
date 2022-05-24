<template>

    <div class="motion-text-display "
         v-bind:class="styling"
    >

        <component
            v-bind:is="displayComponent"
            :motion="motion"
            :truncate-resolutions="truncateResolutions"
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
import ResolutionAmendmentTruncatedTextDisplay from "./resolution-amendment-truncated-text-display";
import ResolutionTextTruncatedDisplay from "./resolution-text-truncated-display";

/**
 * Decides how the format the content of a motion.
 *
 * THIS IS THE PRIMARY MOTION TEXT DISPLAY THAT SHOULD BE USED
 * ALMOST EVERYWHERE
 */
export default {
    name: "motion-text-display",
    components: {
        ResolutionTextTruncatedDisplay,
        ResolutionAmendmentTruncatedTextDisplay,
        ResolutionAmendmentTextDisplay,
        RegularAmendmentTextDisplay,
        AmendmentTextDisplay, PlainTextPrimaryMotionTextDisplay, ResolutionTextDisplay
    },
    props: [
        'motion',
        /** Since this is used in different contexts, the styling gets passed in from the parent */
        'motionStyle',
        'truncateResolutions'
    ],

    mixins: [motionObjectMixin, AmendmentMixin],

    data: function () {
        return {}
    },

    asyncComputed: {

        displayComponent: function () {
            if (isReadyToRock(this.motion)) {

                if (this.isAmendment) {
                    //This display class will handle both amendments to resolutions
                    //and regular amendments (since they require similar text-preprocessing)
                    return AmendmentTextDisplay;
                }

                else if(this.isResolution) {
                    if(this.truncateResolutions){
                        return ResolutionTextTruncatedDisplay
                    }
                    //Motion is primary resolution. We know it's not an
                    //amendment since that would've been caught above.
                    return ResolutionTextDisplay;

                } else {
                    // Motion is a regular old motion
                    return PlainTextPrimaryMotionTextDisplay;
                }

                //
                //
                //
                // switch (this.motion) {
                //     case this.isResolution && ! this.isAmendment:
                //         //Motion is primary resolution
                //         return ResolutionTextDisplay;
                //         break;
                //
                //     case this.isResolution && this.isAmendment:
                //         //Motion is an amendment to a resolution
                //         return ResolutionAmendmentTextDisplay;
                //         break;
                //
                //     case this.isAmendment:
                //         //Motion is an amendment to a non-resolution motion
                //         return RegularAmendmentTextDisplay;
                //         break;
                //
                //     default:
                //         return PlainTextPrimaryMotionTextDisplay;
                //
                // }


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
