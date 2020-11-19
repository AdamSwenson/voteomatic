<template>
    <div class="main-motion-setup-area card ">
        <div class="card-header">
            <h4 class="card-title">{{ title }}</h4>
        </div>

        <div class="closed-notice card-body" v-if="isMotionComplete">
            <h6 class="card-title">Voting has ended. The motion cannot be edited.</h6>
        </div>

        <div class="card-body v-else">
            <div class="required">
                <form>

                    <motion-content-input
                        :motion="draftMotion"
                        v-on:update:content="draftMotion.content  = $event"
                    ></motion-content-input>

                    <vote-required-inputs
                        :motion="draftMotion"
                        v-on:update:requires="draftMotion.requires  = $event"
                    ></vote-required-inputs>

                </form>

                <p class="text-muted">A blank motion has been created. Your entries are automatically saved on the
                    server.</p>
                <p class="text-muted">If you do not type anything, the pending motion will be blank. Use the delete
                    button to fix this.</p>
            </div>

            <div class="optional">
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
            </div>

            <div class="row">
                <div class="col text-center">

                    <delete-motion-button></delete-motion-button>

                    <delete-motion-modal></delete-motion-modal>
                </div>

                <div class="col text-center">
                    <button class="btn btn-primary"
                            data-toggle="modal"
                            data-target="#confirmMotionModal"
                            >Make motion</button>

                    <create-motion-modal :motion="draftMotion"
                                    v-on:confirmed="handleDone"
                ></create-motion-modal>
                </div>

            </div>

        </div>

        <div class="card-footer make-button-area">
            <p class="text-danger" v-if="! isMotionComplete">Use this to correct minor clerical errors. <strong>Do not
                use
                it for formal amendments.</strong></p>

        </div>
    </div>

</template>

<script>
import MotionContentInput from "./motion-content-input";
import VoteRequiredInputs from "./vote-required-inputs";
import DeleteMotionButton from "./delete-motion-button";
import DeleteMotionModal from "./delete-motion-modal";
import MotionMixin from '../../storeMixins/motionMixin';
import MeetingMixin from "../../storeMixins/meetingMixin";
import DescriptionInput from "./description-input";
import MotionTypeInput from "./motion-type-input";
import Motion from "../../../models/Motion";
import Payload from "../../../models/Payload";
import CreateMotionModal from "./create-motion-modal";

export default {
    name: "main-motion-setup-area",
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
            if (this.isMotionComplete) {
                return "Create motion";
            }
            return "Edit motion";

        },

    },
    methods: {
        handleUpdate: function (payload) {
            payload = payload[0];
            window.console.log(payload);
            this.draftMotion[payload.updateProp] = payload.updateVal;
        },

        handleDone: function () {
            let me = this;
            //create it on the server and set it as active
            this.$store.dispatch('createMotion', this.meeting.id)
                .then(function () {
                    //update all the properties stored in draftMotion
                    let p = new Promise((resolve, reject) => {
                        _.forEach(_.keys(me.draftMotion), function (k) {
                            let pl = Payload.factory({
                                'object': me.motion,
                                'updateProp': k,
                                'updateVal': me.draftMotion[k]
                            });
                            me.$store.dispatch('updateMotion', pl);
                        });
                        return resolve();
                    });
                    p.then(function () {
                        me.$router.push('meeting-home');
                    });
                });


        }
    },

    mounted() {
        this.draftMotion = {
            requires : 0.5,
            content : '',
            type : 'main',
            description : ''
        }; //new Motion();
    }

}
</script>

<style scoped>

</style>
