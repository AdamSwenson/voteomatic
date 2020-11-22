<template>

    <div class="amendment-setup-area card">

        <div class="card-body">
            <div class="row display-area text-center">
                <div class="col">
                    <blockquote class="blockquote mb-0">
                        <amendment-text-display :amendment-text="localText"
                                                :original-text="originalText"
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

                        <textarea id="editText"
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
                    <button class="btn btn-danger"
                            v-on:click="handleClick"
                    >Propose Amendment
                    </button>
                </div>

            </div>

        </div>
    </div>

</template>

<script>

import MotionMixin from "../../mixins/motionMixin";
import MeetingMixin from "../../mixins/meetingMixin";
import Payload from "../../models/Payload";
import AmendmentTextDisplay from "./amendment-text-display";

export default {
    name: "amendment-setup-area",
    components: {AmendmentTextDisplay},
    props: [],

    mixins: [MotionMixin, MeetingMixin],

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

        }
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
