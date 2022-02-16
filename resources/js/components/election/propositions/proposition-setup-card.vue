<template>

    <div class="proposition-setup-card card">
        <div class="card-header">
            <h4 class="card-title">Setup propositions</h4>
        </div>

        <div class="card-body ">
            <create-proposition-button></create-proposition-button>
        </div>
        <!--            <div class="required">-->
        <!--                <form>-->
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

        <div class="card-body">
            <button class="btn btn-primary"
                    data-toggle="modal"
                    data-target="#confirmMotionModal"
            >Make motion
            </button>

            <create-motion-modal :motion="draftMotion"
                                 v-on:confirmed="handleDoneClick"
            ></create-motion-modal>

            <delete-motion-button></delete-motion-button>
            <delete-motion-modal></delete-motion-modal>

            <clear-draft-motion-button
                v-on:hide-editing-card="requestResetEditingCard"
            ></clear-draft-motion-button>
        </div>
        <div class="card-footer">

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

export default {
    name: "proposition-setup",
    components: {
        VoteRequiredInputs,
        CreateMotionModal,
        ClearDraftMotionButton,
        DeleteMotionModal,
        DeleteMotionButton, CreatePropositionButton, PropositionDescriptionInput, PropositionContentInput
    },
    props: [],


    mixins: [ChairMixin, MotionMixin, MeetingMixin],

    data: function () {
        return {}
    },

    asyncComputed: {


        draftMotion: function () {
            return this.$store.getters.getDraftMotion;
        }
    },

    computed: {

        propName: {
            get: function () {
                if (!isReadyToRock(this.draftMotion)) return ''
                return this.draftMotion.name;
            },
            set: function (v) {
                let p = Payload.factory({
                    'updateProp': 'name',
                    'updateVal': v
                });
                this.$store.dispatch('updateDraftMotion', p);
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
        },
        requestResetEditingCard: function () {
            this.$emit('hide-editing-card');
        }
    },
    mounted() {
        //In case we somehow got here without the button
        if (!isReadyToRock(this.draftMotion)) {

            this.$store.dispatch('initializeDraftProposition');

        }
    }

}
</script>

<style scoped>

</style>
