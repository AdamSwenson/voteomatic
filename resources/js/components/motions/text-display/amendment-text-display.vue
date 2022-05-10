<template>
    <div class="amendment-text-display">

        <resolution-amendment-text-display
            v-if="isResolution"
            :original-text="originalText"
            :amendment-text="amendmentText"
        ></resolution-amendment-text-display>

        <regular-amendment-text-display
            v-else
            :original-text="originalText"
            :amendment-text="amendmentText"
        ></regular-amendment-text-display>

    </div>


</template>

<script>
import MotionMixin from "../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../mixins/meetingMixin";
import motionObjectMixin from "../../../mixins/motionObjectMixin";
import ResolutionAmendmentTextDisplay from "./resolution-amendment-text-display";
import RegularAmendmentTextDisplay from "./regular-amendment-text-display";
import AmendmentMixin from "../../../mixins/amendmentMixin";

/**
 * Displays an amendmentText tagged string indicating where
 * changes have been made between the original and the
 * amendment.
 *
 * THIS DECIDES WHETHER TO DISPLAY THE RESOLUTION OR REGULAR AMENDMENT
 * TEXT
 */
export default {
    name: "amendment-text-display",
    components: {RegularAmendmentTextDisplay, ResolutionAmendmentTextDisplay},
    mixins: [MeetingMixin, motionObjectMixin,  AmendmentMixin],


    props: [

        'motion',
        /** When the display is used during the setup process
         * we need to override the usual way of getting altered and original text
         * that is defined in the mixin. That is what these do*/
        'amendmentTextForSetup', 'originalTextForSetup'],



    data: function () {
        return {

        }
    },

    asyncComputed: {

    },

    computed: {}

}
</script>

<style>

.altered-text {
    fw-: bold;
}

.struck {
    text-decoration: line-through;
}

ins {
    text-decoration: underline;
    background-color: #d4fcbc;
}

del {
    text-decoration: line-through;
    background-color: #fbb6c2;
    color: #555;
}


/*
Classes added to the primary amendment text when
displaying a secondary amendment.
*/
.primary-insert {

}

.primary-strike {
}

.primary-strike-insert {
}
</style>
