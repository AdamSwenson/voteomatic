<template>

    <div class="proposition-setup-card card">
        <div class="card-header">
            <h4 class="card-title">Setup propositions</h4>
        </div>

        <div class="row">
            <div class="col-lg-3">

                <proposition-list-card
                    v-on:edit-requested="setEditMode"
                    v-on:new-mode-requested="removeEditMode"
                ></proposition-list-card>

            </div>


            <div class="col-lg-9">
                <div class="inputs card " v-if="showInputs">
                    <div class="card-body">
                        <form>

                            <div class="form-group">
                                <label class="form" for="propName">Name</label>
                                <input type="text" class="form-control" id="propName" v-model="propName"/>
                            </div>
                        </form>


                        <proposition-content-input
                            :motion="draftMotion"
                            v-on:update:content="draftMotion.content  = $event"
                        ></proposition-content-input>
                    </div>
                    <div class="card-body">
                        <proposition-description-input
                            :motion="draftMotion"
                            v-on:update:description="draftMotion.description  = $event"
                        ></proposition-description-input>

                        <vote-required-inputs
                            v-if="isChair"
                            :motion="draftMotion"
                            v-on:update:requires="draftMotion.requires  = $event"
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
                    <div class="card-footer" v-else>

                        <button
                            class="btn btn-primary"
                            data-toggle="modal"
                            data-target="#confirmMotionModal"
                        >Save proposition
                        </button>

                        <create-motion-modal :motion="draftMotion"
                                             v-on:confirmed="handleDoneClick"
                        ></create-motion-modal>
                        <clear-draft-motion-button
                            v-on:hide-editing-card="handleClear"
                        ></clear-draft-motion-button>

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
import OfficeListCard from "../setup/office-list-card";
import PropositionListCard from "./proposition-list-card";

export default {
    name: "proposition-setup",
    components: {
        PropositionListCard,
        OfficeListCard,
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
            // showInputs: false

            /** In edit mode we operate on the active motion.
             * Otherwise we operate on draft motion */
            editMode: false
        }
    },

    asyncComputed: {


        draftMotion: function () {
            if (this.editMode) return this.motion;

            return this.$store.getters.getDraftMotion;
        },

        showInputs: function () {
            return isReadyToRock(this.draftMotion);
        }
    },

    computed: {

        propName: {
            get: function () {
                if (!isReadyToRock(this.draftMotion, 'info')) return ''
                return this.draftMotion.name;
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

            }
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
            // this.$store.dispatch('updateDraftMotion', payload);
            //this.draftMotion[event.updateProp] = event.updateVal;
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

        setEditMode: function () {
            this.editMode = true;
        },

        removeEditMode: function () {
            this.editMode = false;
        }

    },
    mounted() {
        //In case we somehow got here without the button
        if (!isReadyToRock(this.draftMotion)) {

        }
    }

}
</script>

<style scoped>

</style>
