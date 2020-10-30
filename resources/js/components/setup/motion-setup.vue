<template>

    <div class="motion-setup card">
        <div class="card-header">
            <h1>Set up motion</h1>
        </div>

        <div class="card-body">
            <button class="btn btn-primary"
                    v-on:click="handleClick"
            >Create new motion
            </button>

            <div class="setup-fields" v-if="showFields">

                <p class="card-text">

                    <label for="motion-content">It is moved that </label>
                    <textarea id="motion-content" v-model="content"
                              placeholder="placeholders.content"></textarea>
                </p>


                <p class="card-text">

                    <label for="description">Optional description or instructions</label>
                    <textarea id="description" v-model="description"
                              placeholder="placeholders.description"></textarea>
                </p>


                <p class="card-text">
                    todo
                    <label for="typeSelect">Motion type</label>
                    <select
                        id="typeSelect"
                        v-model="motionType">
                        <option disabled value="">Please select motion type</option>
                        <option>Main motion</option>
                        <option>Amendment</option>
                        <option>Procedural motion</option>
                    </select>

                    <span>Selected: {{ motionType }}</span>
                </p>
                <p class="card-text">
                    todo
                    <label for="requiresSelect">Vote required to pass</label>
                    <select
                        id="requiresSelect"
                        v-model="requires">
                        <option disabled value="">Please select required vote</option>
                        <option value="0.5">Majority</option>
                        <option value="0.66">Two-thirds</option>

                    </select>
                    <span>Selected: {{ requires }}</span>
                </p>
            </div>
        </div>

    </div>
</template>

<script>

// todo DEV!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
let meetingId = 1;

import * as routes from "../../routes";
import Meeting from '../../models/Meeting';
import MeetingMixin from '../storeMixins/meetingMixin';
import MotionMixin from '../storeMixins/motionMixin';
import Payload from "../../models/Payload";


export default {
    name: "motion-setup",
    props: ['existingMotion'],

    mixins: [MeetingMixin, MotionMixin],

    data: function () {
        return {
            // motion: null,
            showFields: false,
            placeholders: {
                content: "Moved that tacos be eaten everyday.",
                description: "OPTIONAL"
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
                this.$store.dispatch('updateMotion', p);


                // this.motion.content = v;
                // this.updateMotion();
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
                        'updateVal': v
                    }
                );

                this.$store.dispatch('updateMotion', p);

            }
        }
    },

    methods: {
        initializeMotion: function () {


            let p = this.$store.dispatch('createMotion', this.meetingId);
            let me = this;
            p.then(() => {
                this.showFields = true;

            });

            //
            // let p = new Promise(((resolve, reject) => {
            //
            //     //send to server
            //
            //     let url = routes.motions.resource();
            //
            //
            //     // todo DEV!!!!!!!!!!!!!!!!!!!!!!!!!
            //     let params = {meeting_id: meetingId};
            //     // let params = {meetingId: this.meeting.id};
            //
            //
            //     return Vue.axios.post(url, params).then((response) => {
            //         let d = response.data;
            //         this.motion = new Motion(d.id, d.content, d.description, d.requires);
            //         return resolve(this.motion);
            //
            //     });
            // }));
        },

        // updateMotion: function () {
        //     let p = new Promise(((resolve, reject) => {
        //         //send to server
        //         let url = routes.motions.resource(this.motion.id);
        //         // Vue.axios.put(url,  this.meeting).then((response) => {
        //         // Vue.axios.post(url, this.meeting).then((response) => {
        //         Vue.axios.post(url, {data: this.motion, _method: 'put'}).then((response) => {
        //             let d = response.data;
        //             resolve()
        //         });
        //     }));
        // },


        handleClick: function () {
            this.initializeMotion();

        }

    }
}
</script>

<style scoped>

</style>
