<template>

    <div class="amendment-setup-area card">

        <div class="card-body">
            <div class="row display-area text-center">
                <div class="col">
                    <blockquote class="blockquote mb-0">
<!--                        <resolution-amendment-text-display-->
<!--                            v-if="isResolution"-->
<!--                            :amendment-text="localText"-->
<!--                            :original-text="originalText"-->
<!--                        ></resolution-amendment-text-display>-->


                        <amendment-text-display
                            :amendment-text-for-setup="localText"
                            :original-text-for-setup="originalText"
                        ></amendment-text-display>
                    </blockquote>

                    <!--                    <h4>Amendment display</h4>-->
                    <!--                    <div class="clearfix"></div>-->
                    <!--                    <p v-html="taggedNewText"></p>-->
                </div>
            </div>

            <div class="row edit-area">
                <div class="col">
                    <div class="form-group">

                        <label for="editText">Edit amendment</label>

                        <wysiwyg v-model="text"
                        v-if="isResolution"
                        ></wysiwyg>

                        <textarea
                            v-else
                            id="editText"
                                  class="form-control"
                                  v-model="text"
                                  cols="30"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col">
                    <button class="btn btn-primary"
                            v-on:click="handleReset"
                    >Reset to original
                    </button>
                </div>

                <div class="col">

                    <propose-amendment-button
                        v-on:propose-amendment="handleClick"
                    ></propose-amendment-button>

                </div>

            </div>

        </div>
    </div>

</template>

<script>

import MotionMixin from "../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../mixins/meetingMixin";
import motionObjectMixin from "../../../mixins/motionObjectMixin";
import Payload from "../../../models/Payload";
import AmendmentTextDisplay from "../text-display/amendment-text-display";
import ProposeAmendmentButton from "../motion-setup-inputs/propose-amendment-button";
import ResolutionAmendmentTextDisplay from "../text-display/resolution-amendment-text-display";

export default {
    name: "amendment-setup-area",
    components: {ResolutionAmendmentTextDisplay, ProposeAmendmentButton, AmendmentTextDisplay},
    props: [],

    mixins: [MotionMixin, MeetingMixin, motionObjectMixin],

    data: function () {
        return {

            localText: '',

            /**
             * Classes to attach to a word for different purposes
             */
            tags: {
                altered: 'text-monospace',
                inserted: 'text-danger',
                struck: 'struck',
            }
        }
    },

    asyncComputed: {
        originalText: function () {
            if (_.isUndefined(this.motion)) return ''
            return this.motion.content;
        },


        // isResolution: function(){
        //     if(_.isUndefined(this.motion)) return false;
        //     return this.motion.isResolution;
        // },

    },

    computed: {
        text: {
            get: function () {
                if (this.localText === '') {
                    if (_.isUndefined(this.motion)) return ''
                    this.localText = this.motion.content;
                }
                return this.localText;

            },
            set: function (v) {
                this.localText = v;
            }
        },


    },

    methods: {
        handleClick: function () {
            let payload = {
                meetingId: this.meeting.id,
                applies_to: this.motion.id,
                content: this.localText,
                type: 'amendment',
                is_resolution: this.motion.isResolution,
                requires: 0.5
            };
            let me = this;
            let p = this.$store.dispatch('createSubsidiaryMotion', payload);
            p.then(() => {
                me.$router.push('meeting-home');
            });

        },

        /**
         * Removes existing edits
         */
        handleReset: function () {
            this.localText = this.motion.content;
        }


    }

}
</script>

<style scoped>


</style>
