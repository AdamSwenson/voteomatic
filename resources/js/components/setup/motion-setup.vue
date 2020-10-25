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
                        <option>Majority</option>
                        <option>Two-thirds</option>

                    </select>
                    <span>Selected: {{ requires }}</span>
                </p>
            </div>
        </div>

    </div>
</template>

<script>
import * as routes from "../../routes";
import Meeting from '../../models/Meeting';
import Motion from '../../models/Motion';

// todo DEV!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
let meetingId = 1;


export default {
    name: "motion-setup",
    props: ['existingMotion', 'meeting'],
    data: function () {
        return {
            motion: null,
            showFields: false,
            placeholders: {
                content: "Moved that tacos be eaten everyday.",
                description: "OPTIONAL"
            }
        }
    },

    computed: {

        // motion: function () {
        //     if (!_.isUndefined(this.existingMotion)) return this.existingMotion;
        //
        //     return new Promise(((resolve, reject) => {
        //         //send to server
        //         let url = routes.motions.resource();
        //         let params = {meetingId: this.meeting.id};
        //         return Vue.axios.post(url, params).then((response) => {
        //             let d = response.data;
        //             let motion = new Motion(d.id, d.content, d.description, d.requires);
        //             return resolve(motion);
        //         });
        //     }));

        // },

        motionType: {
            get: function () {
                try {
                    return this.motion.type;
                } catch (e) {
                    return ''
                }
            },
            set: function (v) {
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
                this.motion.content = v;
                this.updateMotion();
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
                this.motion.description = v;
                this.updateMotion();
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
                this.motion.requires = v;
                this.updateMotion();
            }
        }
    },

    methods: {
        initializeMotion: function () {

            let p = new Promise(((resolve, reject) => {

                //send to server

                let url = routes.motions.resource();


                // todo DEV!!!!!!!!!!!!!!!!!!!!!!!!!
                let params = {meeting_id: meetingId};
                // let params = {meetingId: this.meeting.id};


                return Vue.axios.post(url, params).then((response) => {
                    let d = response.data;
                    this.motion = new Motion(d.id, d.content, d.description, d.requires);
                    return resolve(this.motion);

                });
            }));
        },

        updateMotion: function () {
            let p = new Promise(((resolve, reject) => {
                //send to server
                let url = routes.motions.resource(this.motion.id);
                // Vue.axios.put(url,  this.meeting).then((response) => {
                // Vue.axios.post(url, this.meeting).then((response) => {
                Vue.axios.post(url, {data: this.motion, _method: 'put'}).then((response) => {
                    let d = response.data;
                    resolve()
                });
            }));
        },


        handleClick: function () {
            this.initializeMotion();
            this.showFields = true;

            // return new Promise(((resolve, reject) => {
            //     //send to server
            //     // let url = routes.motion.resource();
            //     Vue.axios.post(this.url).then((response) => {
            //         let d = response.data;
            //         this.motion = new Motion(d.id);
            //         resolve()
            //
            //     });
            // }));
        }

    }
}
</script>

<style scoped>

</style>
