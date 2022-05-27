<template>
    <div class="main-motion-setup-area card ">
        <div class="card-header">
            <h4 class="card-title">{{ title }}</h4>
        </div>


        <div class="card-body required">
<!--            <div class="required">-->
                <form>

                    <motion-content-input
                        :motion="draftMotion"
                        v-on:update:content="draftMotion.content  = $event"
                    ></motion-content-input>

                    <vote-required-inputs
                        v-if="isChair"
                        :motion="draftMotion"
                        v-on:update:requires="draftMotion.requires  = $event"
                    ></vote-required-inputs>

                </form>

            </div>

            <div class="card-body optional"
                 v-if="isChair"
            >
                <h4 class="card-subtitle text-center">Optional</h4>

                <form>

                    <motion-type-input
                        :motion="draftMotion"
                        v-on:update:type="draftMotion.type  = $event"
                    ></motion-type-input>

                    <description-input
                        :motion="draftMotion"
                        v-on:update:description="draftMotion.description  = $event"
                    ></description-input>

                </form>
<!--            </div>-->
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col text-start"
                     v-if="isChair"
                >
                    <div class="d-grid gap-2">
                        <delete-motion-button></delete-motion-button>
                    </div>
                    <delete-motion-modal></delete-motion-modal>
                </div>

                <div class="col text-center">
                    <div class="d-grid gap-2">
                        <clear-draft-motion-button
                            v-on:hide-editing-card="requestResetEditingCard"
                        ></clear-draft-motion-button>
                    </div>
                </div>

                <div class="col text-center">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary"
                                data-bs-toggle="modal"
                                data-bs-target="#confirmMotionModal"
                        >Make motion
                        </button>
                    </div>

                    <create-motion-modal :motion="draftMotion"
                                         v-on:confirmed="handleDoneClick"
                    ></create-motion-modal>
                </div>

            </div>

        </div>
    </div>
    <!--        <div class="card-footer make-button-area"-->
    <!--             v-if="isChair"-->
    <!--        >-->
    <!--            <p class="text-danger" v-if="! isMotionComplete">Use this to correct minor clerical errors. <strong>Do not-->
    <!--                use-->
    <!--                it for formal amendments.</strong></p>-->

    <!--        </div>-->


</template>

<script>
import MotionContentInput from "./motion-content-input";
import VoteRequiredInputs from "./vote-required-inputs";
import ChairMixin from "../../../mixins/chairMixin";
import DeleteMotionButton from "./delete-motion-button";
import DeleteMotionModal from "./delete-motion-modal";
import MotionMixin from '../../../mixins/motionStoreMixin';
import MeetingMixin from "../../../mixins/meetingMixin";
import DescriptionInput from "./description-input";
import MotionTypeInput from "./motion-type-input";
import Motion from "../../../models/Motion";
import Payload from "../../../models/Payload";
import CreateMotionModal from "./create-motion-modal";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import ClearDraftMotionButton from "./clear-draft-motion-button";
import ResolutionInput from "../resolutions/resolution-input";

export default {
    name: "main-motion-setup-area",
    components: {
        ResolutionInput,
        ClearDraftMotionButton,
        CreateMotionModal,
        MotionTypeInput,
        DescriptionInput,
        DeleteMotionModal,
        DeleteMotionButton, VoteRequiredInputs, MotionContentInput
    },
    props: [],

    mixins: [ChairMixin, MotionMixin, MeetingMixin],

    data: function () {
        return {
            // draftMotion: null
        }
    },

    computed: {
        title: function () {
            return "Create motion";
        },

        draftMotion: function () {
            return this.$store.getters.getDraftMotion;
        }

    },
    methods: {
        handleUpdate: function (event) {
            //Going to handle updates here, outside of the
            //input component so can distinguish between
            //creating and editing if we decide to keep the
            //editing function.
            let payload = event[0];
            window.console.log(payload);
            this.$store.dispatch('updateDraftMotion', payload);
            //this.draftMotion[event.updateProp] = event.updateVal;
        },

        /**
         * This formally makes the motion, i.e., saves it to the
         * server.
         *
         */
        handleDoneClick: function () {
            let me = this;
            this.$store.dispatch('createMotionFromDraft').then(() => {
                //clear draft motion and hide the window.
                me.requestResetEditingCard();
                me.$store.commit('clearDraftMotion');
            });

            // let me = this;
            // //create it on the server and set it as active
            // this.$store.dispatch('createMotion', this.meeting.id)
            //     .then(function () {
            //         //update all the properties stored in draftMotion
            //         let p = new Promise((resolve, reject) => {
            //             _.forEach(_.keys(me.draftMotion), function (k) {
            //                 let pl = Payload.factory({
            //                     'object': me.motion,
            //                     'updateProp': k,
            //                     'updateVal': me.draftMotion[k]
            //                 });
            //                 me.$store.dispatch('updateMotion', pl);
            //             });
            //             return resolve();
            //         });
            //         p.then(function () {
            //             me.$router.push('meeting-home');
            //         });
            //     });


        },

        requestResetEditingCard: function () {
            this.$emit('hide-editing-card');
        }
    },

    mounted() {
        //In case we somehow got here without the button
        if (!isReadyToRock(this.draftMotion)) {
            this.$store.dispatch('initializeDraftMotion');
        }

        // this.draftMotion = {
        //     requires : 0.5,
        //     content : '',
        //     type : 'main',
        //     description : ''
        // }; //new Motion();
    }

}
</script>

<style scoped>

</style>
