<template>

    <div class="resolution-setup-area card">

        <div class="card-body">
            <div class="row display-area ">
                <div class="col">
                    <blockquote class="blockquote ml-3 mb-0">

                        <resolution-text-display
                            :motion="draftMotion"
                        ></resolution-text-display>

                    </blockquote>
                </div>
            </div>
        </div>

        <div class="card-body ">
            <form>
                <div class="mb-2">
                    <resolution-title-input :motion="draftMotion"></resolution-title-input>
                </div>

                <div class="mb-2">
                    <resolution-identifier-input :motion="draftMotion"></resolution-identifier-input>
                </div>
            </form>

        </div>

        <div class="card-body">
            <resolution-input :motion="draftMotion"></resolution-input>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary"
                                v-on:click="handleReset"
                        >Clear entry
                        </button>
                    </div>
                </div>

                <div class="col">
                    <div class="d-grid gap-2">
                        <propose-resolution-button></propose-resolution-button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--    </div>-->

</template>

<script>

import MotionMixin from "../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../mixins/meetingMixin";

import motionObjectMixin from "../../../mixins/motionObjectMixin";

import resolutionTextDisplay from "../text-display/resolution-text-display";
import ProposeResolutionButton from "./propose-resolution-button";
import ResolutionInput from "./resolution-input";
import ResolutionTitleInput from "./resolution-title-input";
import ResolutionIdentifierInput from "./resolution-identifier-input";

export default {
    name: "resolution-setup-area",
    components: {
        ResolutionIdentifierInput,
        ResolutionTitleInput, ResolutionInput, ProposeResolutionButton, resolutionTextDisplay
    },
    props: [],

    mixins: [MotionMixin, MeetingMixin, motionObjectMixin],

    data: function () {
        return {}
    },

    asyncComputed: {

        draftMotion: function () {
            return this.$store.getters.getDraftMotion;
        }
    },

    computed: {},

    methods: {

        /**
         * Removes existing edits
         */
        handleReset: function () {
            // this.requestResetEditingCard();
            this.$store.commit('clearDraftMotion');
        }


    }

}
</script>

<style scoped>


</style>
