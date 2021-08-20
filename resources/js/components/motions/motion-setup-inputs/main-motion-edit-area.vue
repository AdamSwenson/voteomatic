<template>
    <div class="main-motion-setup-area card ">
        <div class="card-header">
            <h4 class="card-title">{{ title }} <span class="text-danger">(Chair only)</span></h4>
        </div>

        <div class="closed-notice card-body" v-if="isMotionComplete">
            <h6 class="card-title">Voting has ended. The motion cannot be edited.</h6>
        </div>

        <div class="card-body v-else">
            <p class="text-danger"><strong>Only</strong> use this to correct minor clerical errors.</p>

            <h4 class="text-danger">Do not use this for formal amendments.</h4>

            <p class="text-danger">Your edits are automatically saved to the server as you type</p>

            <div class="required">
                <form>

                    <motion-content-input
                        :motion="motion"
                        :edit-mode="true"
                        v-on:update:content="handleUpdate('content', $event)"
                    ></motion-content-input>

                    <vote-required-inputs
                        :motion="motion"
                        :edit-mode="true"
                        v-on:update:requires="handleUpdate('requires', $event)"
                    ></vote-required-inputs>

                </form>

            </div>

            <div class="optional">
                <h4 class="card-subtitle text-center">Optional</h4>

                <form>

                    <motion-type-input
                        :motion="motion"
                        :edit-mode="true"
                        v-on:update:type="handleUpdate('type', $event)"
                    ></motion-type-input>

                    <description-input
                        :motion="motion"
                        :edit-mode="true"
                        v-on:update:description="handleUpdate('description', $event)"
                    ></description-input>

                </form>
            </div>

            <div class="card-footer make-button-area">
                <div class="row">
                    <div class="col text-center">

                        <delete-motion-button></delete-motion-button>

                        <delete-motion-modal></delete-motion-modal>
                    </div>

                    <div class="col text-center">
                        <button class="btn btn-primary"
                                v-on:click="handleDone"
                        >Done editing
                        </button>

                    </div>

                </div>

            </div>


        </div>
    </div>

</template>

<script>
import MotionContentInput from "./motion-content-input";
import VoteRequiredInputs from "./vote-required-inputs";
import DeleteMotionButton from "./delete-motion-button";
import DeleteMotionModal from "./delete-motion-modal";
import MotionMixin from '../../../mixins/motionStoreMixin';
import MeetingMixin from "../../../mixins/meetingMixin";
import DescriptionInput from "./description-input";
import MotionTypeInput from "./motion-type-input";
import Motion from "../../../models/Motion";
import Payload from "../../../models/Payload";
import CreateMotionModal from "./create-motion-modal";

export default {
    name: "main-motion-edit-area",
    components: {
        CreateMotionModal,
        MotionTypeInput,
        DescriptionInput,
        DeleteMotionModal,
        DeleteMotionButton, VoteRequiredInputs, MotionContentInput
    },
    props: [],

    mixins: [MotionMixin, MeetingMixin],

    data: function () {
        return {
            draftMotion: null
        }
    },

    computed: {
        title: function () {
            return "Edit motion";
        },

    },
    methods: {
        handleUpdate: function (prop, val) {
            let me = this;
            window.console.log('handleUpdate', prop, val);
            let pl = Payload.factory({
                object: me.motion,
                updateProp: prop,
                updateVal: val
            });
            me.$store.dispatch('updateMotion', pl);
        },

        handleDone: function () {

            this.$store.dispatch('forceNavigationToHome');
            // me.$router.push('meeting-home');

        }
    },


}
</script>

<style scoped>

</style>
