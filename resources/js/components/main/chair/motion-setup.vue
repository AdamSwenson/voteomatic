<template>

    <div class="motion-setup card">


        <div class="card-header make-button-area">
            <create-motion-button
                :meeting="meeting"
                v-on:create-motion-clicked="handleNewButtonClick"
            ></create-motion-button>
        </div>

        <div class="card-header make-button-area">
            <motion-template-buttons></motion-template-buttons>

        </div>

        <div class="card-header">
            <h4 class="card-title">{{ title }}</h4>
        </div>

        <div class="closed-notice card-body" v-if="isMotionComplete">
            <h6 class="card-title">Voting has ended. The motion cannot be edited.</h6>

<!--            <div class="text-right">-->
<!--                <create-motion-button v-on:create-motion-clicked="handleNewButtonClick"></create-motion-button>-->
<!--            </div>-->

        </div>

        <div class="setup-fields card-body " v-else>
            <div class="required">
                <form>

                    <motion-content-input></motion-content-input>


                    <vote-required-inputs></vote-required-inputs>


                </form>
                <!--                <p class="card-text text-sm-left">-->
                <!--                    -->
                <!--                </p>-->
            </div>

            <div class="optional">
                <h4 class="card-subtitle text-center">Optional</h4>

                <form>

                    <motion-type-input></motion-type-input>

                    <description-input></description-input>

                </form>
            </div>

            <delete-motion-button></delete-motion-button>
            <delete-motion-modal></delete-motion-modal>

        </div>

        <!--        <div class="card-body">-->
        <!--            <motion-template-buttons></motion-template-buttons>-->
        <!--        </div>-->

<!--        <div class="card-footer make-button-area">-->
<!--            <create-motion-button v-on:create-motion-clicked="handleNewButtonClick"></create-motion-button>-->
<!--        </div>-->

        <div class="card-footer make-button-area">
            <p class="text-muted">The motion is being saved on the server as you type.</p>
<!--            <motion-template-buttons></motion-template-buttons>-->

        </div>

    </div>

</template>

<script>


import * as routes from "../../../routes";
import Meeting from '../../../models/Meeting';
import MeetingMixin from '../../storeMixins/meetingMixin';
import MotionMixin from '../../storeMixins/motionMixin';
import Payload from "../../../models/Payload";
import VoteRequiredInputs from "../../motions/motion-setup-inputs/vote-required-inputs";
import MotionContentInput from "../../motions/motion-setup-inputs/motion-content-input";
import MotionTypeInput from "../../motions/motion-setup-inputs/motion-type-input";
import DescriptionInput from "../../motions/motion-setup-inputs/description-input";
import MotionTemplateButtons from "../../motions/motion-setup-inputs/motion-template-buttons";
import CreateMotionButton from "../../motions/create-motion-button";
import DeleteMotionButton from "../../motions/motion-setup-inputs/delete-motion-button";
import DeleteMotionModal from "../../motions/motion-setup-inputs/delete-motion-modal";


export default {
    name: "motion-setup",
    components: {
        DeleteMotionModal,
        DeleteMotionButton,
        CreateMotionButton,
        MotionTemplateButtons, DescriptionInput, MotionTypeInput, MotionContentInput, VoteRequiredInputs
    },
    props: ['existingMotion'],

    mixins: [MeetingMixin, MotionMixin],

    data: function () {
        return {
            // motion: null,
            showFields: true,

            placeholders: {}
        }
    },

    computed: {


        title: function () {
            if (this.isMotionComplete) {
                return "Create motion";
            }
            return "Edit motion";

        }
    },

    methods: {
        // initializeMotion: function () {
        //
        //     let p = this.$store.dispatch('createMotion', this.meeting.id);
        //     let me = this;
        //     p.then(() => {
        //
        //     });
        // },


        handleNewButtonClick: function () {
            // this.initializeMotion();

            this.showFields = true;

        }

    },

    mounted() {
        // window.console.log('loaded');
    }
}
</script>

<style scoped>

.setup-fields {
    /*margin-top: 12em;*/

}

.optional {
    margin-top: 4em;
}
</style>
