<template>

    <div class="resolution-setup-area card">

        <div class="card-body">
            <div class="row display-area text-center">
                <div class="col">
                    <blockquote class="blockquote mb-0">

                        <resolution-text-display
                            :motion="draftMotion"
                        ></resolution-text-display>

                    </blockquote>
                </div>
            </div>

            <div class="row edit-area">
                <div class="col">
                    <!--                    <div class="form-group">-->

                    <resolution-input :motion="draftMotion"></resolution-input>

                    <!--                    </div>-->
                </div>
            </div>

            <div class="row">

                <div class="col">
                    <button class="btn btn-primary"
                            v-on:click="handleReset"
                    >Clear entry
                    </button>
                </div>

                <div class="col">

                    <propose-resolution-button></propose-resolution-button>

                </div>

            </div>

        </div>
    </div>

</template>

<script>

import MotionMixin from "../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../mixins/meetingMixin";

import motionObjectMixin from "../../../mixins/motionObjectMixin";

import resolutionTextDisplay from "./resolution-text-display";
import ProposeResolutionButton from "./propose-resolution-button";
import ResolutionInput from "./resolution-input";

export default {
    name: "resolution-setup-area",
    components: {ResolutionInput, ProposeResolutionButton, resolutionTextDisplay},
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
