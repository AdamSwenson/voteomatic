<template>

    <div class="motion-setup card">

        <div class="card-header">
            <h4 class="card-title">{{ title }}</h4>
        </div>


        <div class="closed-notice card-body" v-if="isMotionComplete">
            <h6 class="card-title">Voting has ended. The motion cannot be edited.</h6>
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

        </div>

        <!--        <div class="card-body">-->
        <!--            <motion-template-buttons></motion-template-buttons>-->
        <!--        </div>-->


        <div class="card-footer make-button-area">

            <motion-template-buttons></motion-template-buttons>

            <div class="text-right">
                <button class="btn btn-primary"
                        v-on:click="handleClick"
                >Create new motion
                </button>
            </div>
        </div>

    </div>

</template>

<script>


import * as routes from "../../../routes";
import Meeting from '../../../models/Meeting';
import MeetingMixin from '../../storeMixins/meetingMixin';
import MotionMixin from '../../storeMixins/motionMixin';
import Payload from "../../../models/Payload";
import VoteRequiredInputs from "./inputs/vote-required-inputs";
import MotionContentInput from "./inputs/motion-content-input";
import MotionTypeInput from "./inputs/motion-type-input";
import DescriptionInput from "./inputs/description-input";
import MotionTemplateButtons from "./inputs/motion-template-buttons";


export default {
    name: "motion-setup",
    components: {MotionTemplateButtons, DescriptionInput, MotionTypeInput, MotionContentInput, VoteRequiredInputs},
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
        initializeMotion: function () {

            let p = this.$store.dispatch('createMotion', this.meeting.id);
            let me = this;
            p.then(() => {
                this.showFields = true;

            });
        },


        handleClick: function () {
            this.initializeMotion();

        }

    },

    mounted() {
        window.console.log('loaded');
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
