<template>

    <div class="motion-setup card">

        <div class="card-header make-button-area">
            <h5 class="card-subtitle">Procedural motions</h5>
            <motion-template-buttons></motion-template-buttons>
        </div>

        <div class="card-header make-button-area">
<!--            <h5 class="card-subtitle">Amendments</h5>-->
            <amendment-button
                v-on:new-amendment="handleAmendmentButtonClick"
            ></amendment-button>
<!--        </div>-->

<!--        <div class="card-header make-button-area">-->
<!--            <h5 class="card-subtitle">Substantive main motions</h5>-->

            <create-motion-button
                :meeting="meeting"
                v-on:create-motion-clicked="handleNewMainButtonClick"
             ></create-motion-button>

            <button class="btn btn-outline-danger"
                    v-if="isChair"
                    v-on:click="handleEditMainButtonClick"
            >Edit pending motion</button>
        </div>

        <div class="card-body" v-if="showBody">

            <main-motion-setup-area v-if="showCard === 'main'"
                                    v-on:hide-editing-card="resetCard"

            ></main-motion-setup-area>

            <amendment-setup-area v-else-if="showCard === 'amendment'"></amendment-setup-area>

            <main-motion-edit-area v-else-if="showCard === 'edit' && isChair"></main-motion-edit-area>
        </div>


        <!--        <div class="card-header">-->
        <!--            <h4 class="card-title">{{ title }}</h4>-->
        <!--        </div>-->

        <!--        <div class="closed-notice card-body" v-if="isMotionComplete">-->
        <!--            <h6 class="card-title">Voting has ended. The motion cannot be edited.</h6>-->

        <!--            <div class="text-right">-->
        <!--                <create-motion-button v-on:create-motion-clicked="handleNewButtonClick"></create-motion-button>-->
        <!--            </div>-->

        <!--        </div>-->

        <!--        <div class="setup-fields card-body " v-else>-->
        <!--            <div class="required">-->
        <!--                <form>-->

        <!--                    <motion-content-input></motion-content-input>-->


        <!--                    <vote-required-inputs></vote-required-inputs>-->


        <!--                </form>-->
        <!--                &lt;!&ndash;                <p class="card-text text-sm-left">&ndash;&gt;-->
        <!--                &lt;!&ndash;                    &ndash;&gt;-->
        <!--                &lt;!&ndash;                </p>&ndash;&gt;-->
        <!--            </div>-->

        <!--            <div class="optional">-->
        <!--                <h4 class="card-subtitle text-center">Optional</h4>-->

        <!--                <form>-->

        <!--                    <motion-type-input></motion-type-input>-->

        <!--                    <description-input></description-input>-->

        <!--                </form>-->
        <!--            </div>-->

        <!--            <delete-motion-button></delete-motion-button>-->
        <!--            <delete-motion-modal></delete-motion-modal>-->

        <!--        </div>-->

        <!--        &lt;!&ndash;        <div class="card-body">&ndash;&gt;-->
        <!--        &lt;!&ndash;            <motion-template-buttons></motion-template-buttons>&ndash;&gt;-->
        <!--        &lt;!&ndash;        </div>&ndash;&gt;-->

        <!--&lt;!&ndash;        <div class="card-footer make-button-area">&ndash;&gt;-->
        <!--&lt;!&ndash;            <create-motion-button v-on:create-motion-clicked="handleNewButtonClick"></create-motion-button>&ndash;&gt;-->
        <!--&lt;!&ndash;        </div>&ndash;&gt;-->

        <!--        <div class="card-footer make-button-area">-->
        <!--            <p class="text-danger" v-if="! isMotionComplete">Use this to correct minor clerical errors. <strong>Do not use it for formal amendments.</strong></p>-->
        <!--            <p class="text-muted">The motion is being saved on the server as you type.</p>-->
        <!--&lt;!&ndash;            <motion-template-buttons></motion-template-buttons>&ndash;&gt;-->

        <!--        </div>-->

    </div>

</template>

<script>

import {isReadyToRock} from "../../../utilities/readiness.utilities";
import * as routes from "../../../routes";
import Meeting from '../../../models/Meeting';
import MeetingMixin from '../../../mixins/meetingMixin';
import MotionMixin from '../../../mixins/motionStoreMixin';

import motionObjectMixin from "../../../mixins/motionObjectMixin";
import ChairMixin from "../../../mixins/chairMixin";
import Payload from "../../../models/Payload";
import VoteRequiredInputs from "../../motions/motion-setup-inputs/vote-required-inputs";
import MotionContentInput from "../../motions/motion-setup-inputs/motion-content-input";
import MotionTypeInput from "../../motions/motion-setup-inputs/motion-type-input";
import DescriptionInput from "../../motions/motion-setup-inputs/description-input";
import MotionTemplateButtons from "../../motions/motion-setup-inputs/motion-template-buttons";
import CreateMotionButton from "../../motions/create-motion-button";
import DeleteMotionButton from "../../motions/motion-setup-inputs/delete-motion-button";
import DeleteMotionModal from "../../motions/motion-setup-inputs/delete-motion-modal";
import MainMotionSetupArea from "../../motions/motion-setup-inputs/main-motion-setup-area";
import AmendmentButton from "../../motions/motion-setup-inputs/amendment-button";
import AmendmentSetupArea from "../../motions/amendment-setup-area";
import MainMotionEditArea from "../../motions/motion-setup-inputs/main-motion-edit-area";


export default {
    name: "motion-setup",
    components: {
        MainMotionEditArea,
        AmendmentSetupArea,
        AmendmentButton,
        MainMotionSetupArea,
        DeleteMotionModal,
        DeleteMotionButton,
        CreateMotionButton,
        MotionTemplateButtons, DescriptionInput, MotionTypeInput, MotionContentInput, VoteRequiredInputs
    },
    props: ['existingMotion'],

    mixins: [MeetingMixin, MotionMixin, ChairMixin, motionObjectMixin],

    data: function () {
        return {
            // motion: null,
            showCard: null,

            placeholders: {}
        }
    },

    computed: {
        /**
         * Computed property so will update
         */
        showBody : function(){
          return isReadyToRock(this.showCard);
        },


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
        handleAmendmentButtonClick: function () {
            window.console.log('new amendment');
            this.showCard = 'amendment';
        },

        handleNewMainButtonClick: function () {
            // this.initializeMotion();
            window.console.log('new main');
            this.showCard = 'main';
        },

        handleEditMainButtonClick: function(){
            this.showCard = 'edit';
        },

        /**
         * Hides whatever card is showing
         */
        resetCard : function(){
            this.showCard = null;
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
