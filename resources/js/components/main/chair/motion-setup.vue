<template>

    <div class="motion-setup card">
        <div class="card-header">
            <h4 class="card-title">Create and edit motion</h4>
        </div>

        <div class="card-body">

            <div class="closed-notice" v-if="isMotionComplete">
                <h6 class="card-title">Voting has ended. The motion cannot be edited.</h6>
            </div>

            <div class="setup-fields" v-else>
                <form>

                    <div class="form-group">
                        <label for="motion-content">It is moved that </label>
                        <textarea id="motion-content"
                                  class="form-control"
                                  rows="3"
                                  v-model="content"
                                  v-bind:placeholder="placeholders.content"
                        ></textarea>
                    </div>

                    <div class="form-group">

                        <label for="description">Optional description or instructions</label>
                        <textarea id="description"
                                  class="form-control"
                                  v-model="description"
                                  v-bind:placeholder="placeholders.description"
                        ></textarea>
                    </div>

                    <div class="form-group">
                        <label for="typeSelect">Motion type</label>
                        <select
                            id="typeSelect"
                            class="form-control disabled"
                            v-model="motionType">
                            <option disabled value="">Please select motion type</option>
                            <option>Main motion</option>
                            <option>Amendment</option>
                            <option>Procedural motion</option>
                        </select>
                    </div>

                    <div class="form-group">

                        <label for="requiresSelect">Vote required to pass</label>
                        <select
                            id="requiresSelect"
                            class="form-control "
                            v-model="requires"
                        >
                            <option disabled value="">Please select required vote</option>
                            <option value="0.5">Majority</option>
                            <option value="0.66">Two-thirds</option>

                        </select>

                    </div>

                </form>


            </div>



        </div>

            <div class="card-footer make-button-area">
<!--                <p class="text-right">-->
                    <button class="btn btn-primary"
                            v-on:click="handleClick"
                    >Create new motion
                    </button>
<!--                </p>-->
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


export default {
    name: "motion-setup",
    props: ['existingMotion'],

    mixins: [MeetingMixin, MotionMixin],

    data: function () {
        return {
            // motion: null,
            showFields: true,
            placeholders: {
                content: "that tacos be declared the official food of this body.",
                description: "(This is currently unused)"
            }
        }
    },

    computed: {

        motionType: {
            get: function () {
                try {
                    return this.motion.type;
                } catch (e) {
                    return ''
                }
            },
            set: function (v) {
                let p = Payload.factory({
                        'object': this.motion,
                        'updateProp': 'type',
                        'updateVal': v
                    }
                );
                this.$store.dispatch('updateMotion', p);

            }
        },

        content: {
            get: function () {
                if (_.isUndefined(this.motion) || _.isNull(this.motion)) {
                    return '';
                }
                return this.motion.content;
            },
            set(v) {
                let p = Payload.factory({
                        'object': this.motion,
                        'updateProp': 'content',
                        'updateVal': v
                    }
                );

                if (_.isUndefined(this.motion) || _.isNull(this.motion)) {
                    //initialize first if no motion exists
                    let me = this;
                    this.$store.dispatch('createMotion', this.meeting.id).then(function(){
                        me.$store.dispatch('updateMotion', p);
                    });

                }else{
                    //otherwise we can just update as normal
                    this.$store.dispatch('updateMotion', p);
                }
            }
        },


        description: {
            get: function () {
                try {
                    return this.motion.description;
                } catch (e) {
                    return ''
                }
            },
            set(v) {
                let p = Payload.factory({
                        'object': this.motion,
                        'updateProp': 'description',
                        'updateVal': v
                    }
                );

                this.$store.dispatch('updateMotion', p);

            }
        },

        requires: {
            get: function () {
                try {
                    return this.motion.requires;
                } catch (e) {
                    return ''
                }
            },
            set(v) {
                let p = Payload.factory({
                        'object': this.motion,
                        'updateProp': 'requires',
                        'updateVal': _.toNumber(v)
                    }
                );

                this.$store.dispatch('updateMotion', p);

            }
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

    }
}
</script>

<style scoped>

.setup-fields {
    /*margin-top: 12em;*/
}
</style>
