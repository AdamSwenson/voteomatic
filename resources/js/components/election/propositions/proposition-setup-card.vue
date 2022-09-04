<template>

    <div class="proposition-setup-card card">

        <div class="row   mt-2">

            <div class="col-lg-3">

                <proposition-list-card
                ></proposition-list-card>
<!--                v-on:edit-requested="setEditMode"-->
            </div>

            <div class="col-lg-9">
                <div class="inputs card " v-if="showInputs">

                    <div class="card-header">
                        <h4 class="card-subtitle">{{ title }}</h4>
                    </div>

                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label class='form-label' for="propName">Name</label>
                                <input type="text" class="form-control" id="propName" v-model="propName"/>
                            </div>
                        </form>
                    </div>

                    <div class="card-body">
                        <proposition-content-input
                            :motion="editedMotion"
                            :edit-mode="editMode"
                            v-on:update:content="editedMotion.content  = $event"
                        ></proposition-content-input>
                    </div>

                    <div class="card-body">
                        <proposition-description-input
                            :motion="editedMotion"
                            :edit-mode="editMode"
                            v-on:update:description="editedMotion.description  = $event"
                        ></proposition-description-input>
                    </div>

                    <div class="card-body">
                        <vote-required-inputs
                            v-if="isChair"
                            :motion="editedMotion"
                            :edit-mode="editMode"
                            v-on:update:requires="editedMotion.requires  = $event"
                        ></vote-required-inputs>

                    </div>

                    <div class="card-body" v-if="editMode">
                        <p class="text-muted">Edits are saved automatically as you type</p>
                    </div>

                    <!--                    If we are editing an existing prop    -->
                    <div class="card-footer" v-if="editMode">

                        <delete-motion-button></delete-motion-button>
                        <delete-motion-modal></delete-motion-modal>

                    </div>

                    <!--                        If we are working on something new-->
                    <div class="card-footer" v-if="!editMode">
                        <button
                            class="btn btn-primary"
                            v-on:click="handleDoneClick"
                        >Save proposition
                        </button>

                        <clear-draft-motion-button
                            v-on:hide-editing-card="handleClear"
                        ></clear-draft-motion-button>


                        <!--                        <button-->
<!--                            class="btn btn-primary"-->
<!--                            data-bs-toggle="modal"-->
<!--                            data-bs-target="#confirmMotionModal"-->
<!--                        >Save proposition-->
<!--                        </button>-->

<!--                        <create-motion-modal :motion="editedMotion"-->
<!--                                             v-on:confirmed="handleDoneClick"-->
<!--                        ></create-motion-modal>-->
<!--                        -->

                    </div>

                </div>

            </div>
        </div>
    </div>


</template>

<script>
import PropositionContentInput from "./proposition-content-input";
import PropositionDescriptionInput from "./proposition-description-input";
import CreatePropositionButton from "./create-proposition-button";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import DeleteMotionButton from "../../motions/motion-setup-inputs/delete-motion-button";
import DeleteMotionModal from "../../motions/motion-setup-inputs/delete-motion-modal";
import ClearDraftMotionButton from "../../motions/motion-setup-inputs/clear-draft-motion-button";
import ChairMixin from "../../../mixins/chairMixin";
import MotionMixin from "../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../mixins/meetingMixin";
import CreateMotionModal from "../../motions/motion-setup-inputs/create-motion-modal";
import VoteRequiredInputs from "../../motions/motion-setup-inputs/vote-required-inputs";
import Payload from "../../../models/Payload";
import PropositionListCard from "./proposition-list-card";

export default {
    name: "proposition-setup",
    components: {
        PropositionListCard,
        VoteRequiredInputs,
        CreateMotionModal,
        ClearDraftMotionButton,
        DeleteMotionModal,
        DeleteMotionButton, CreatePropositionButton, PropositionDescriptionInput, PropositionContentInput
    },
    props: [],


    mixins: [ChairMixin, MotionMixin, MeetingMixin],

    data: function () {
        return {

        }
    },

    asyncComputed: {
        /**
         * Either the currently selected motion object
         * or a draft motion object, depending on what mode
         * we are in.
         */
        editedMotion: function () {
            //If a draft proposition exists, that's the one we
            //want to be working on. If a regular motion also exists and
            //is set as current, we supersede it.
            let draft =  this.$store.getters.getDraftMotion;
            if(isReadyToRock(draft) && draft.type === 'proposition') return draft;

            //Otherwise we use the current motion, assuming it is a proposition
            if (isReadyToRock(this.motion) && this.motion.type === 'proposition') return this.motion;
        },

        /** In edit mode we operate on the active motion.
         * Otherwise we operate on draft motion.
         * This matters because in edit mode, there is no need to
         * click save.
         * Whether we are in edit mode is determined by looking at
         * the edited motion
         * */
        editMode: function(){
            //we are editing if there is a motion set and it has same id as the editedMotion
            return isReadyToRock(this.editedMotion) && isReadyToRock(this.motion, 'id') && this.editedMotion.id === this.motion.id;
         //  return (isReadyToRock(this.motion) && this.motion.type === 'proposition')
        },

        showInputs: function () {
            // return this.editMode;
            return isReadyToRock(this.editedMotion) || (isReadyToRock(this.motion) && this.motion.type === 'proposition');
        },

        title: function () {
            let defaultTitle = "Proposition setup";
// let name = this.editedMotion.info.name;
            if (!isReadyToRock(this.editedMotion) || !isReadyToRock(this.editedMotion.info) || !isReadyToRock(this.editedMotion.info.name) || this.editedMotion.info.name.length <1) return defaultTitle

            return this.editedMotion.info.name;

        }
    },

    computed: {
        //Needs to be computed so that can handle updates

        propName: {
            get: function () {
                if (isReadyToRock(this.editedMotion)) return this.editedMotion.info.name;
                // return this.editedMotion.name;
            },

            set: function (v) {
                let p = Payload.factory({
                    'updateProp': 'name',
                    'updateVal': v
                });

                if (this.editMode) {
                    this.$store.dispatch('updateMotion', p);
                } else {
                    this.$store.dispatch('updateDraftMotion', p);
                }

            },
            watch : ['editedMotion']
        },

    },

    methods: {
        handleUpdate: function (event) {
            //Going to handle updates here, outside of the
            //input component so can distinguish between
            //creating and editing if we decide to keep the
            //editing function.
            let payload = event[0];
            window.console.log(payload);
            if (this.editMode) {
                this.$store.dispatch('updateMotion', payload);
            } else {
                this.$store.dispatch('updateDraftMotion', payload);
            }
            // this.$store.dispatch('updateeditedMotion', payload);
            //this.editedMotion[event.updateProp] = event.updateVal;
        },

        /**
         * This formally makes the motion, i.e., saves it to the
         * server.
         *
         */
        handleDoneClick: function () {
            let me = this;
            this.$store.dispatch('createPropositionFromDraft').then(() => {

            });
        },

        handleClear: function () {

        },

        // setEditMode: function () {
        //     // this.editMode = true;
        // },
        //
        // removeEditMode: function () {
        //     // this.editMode = false;
        // },



    },
    mounted() {
        //In case we somehow got here without the button
        if (!isReadyToRock(this.editedMotion)) {

        }
    }

}
</script>

<style scoped>

</style>
