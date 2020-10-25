<template>
    <div class="meeting-setup card">
        <div class="card-header">
            <h1>Set up meeting</h1>
        </div>
        <div class="card-body">
            <div class="card-text">
                <button class="btn btn-primary"
                        v-on:click="handleClick"
                >Create new meeting
                </button>

                <div class="setup-fields" v-if="showFields">

                    <label for="meeting-name">Meeting name</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="meeting-name" v-model="meetingName">
                    </div>

                    <label for="meeting-date">Meeting date</label>
                    <div class="input-group mb-3">
                        <input type="date" class="form-control" id="meeting-date" v-model="meetingDate">
                    </div>

                    <div class="roster-area">
                        <h4>Meeting roster</h4>

                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>

import * as routes from "../../routes";
import Meeting from '../../models/Meeting';

export default {
    name: "meeting-setup",
    props: ['existingMeeting'],
    data: function () {
        return {
            showFields: false,

            meeting: null
        }
    },


    computed: {
        meetingDate: {
            get: function () {
                try {
                    return this.meeting.date;
                } catch (e) {
                    return ''
                }
            },

            set(v) {
                this.meeting.date = v;
                //
                this.updateMeeting();
                //     let p = new Promise(((resolve, reject) => {
                //         // console.log(routes.meetings);
                //         //send to server
                //         // let url = routes.meetings.resource(this._meeting.id);
                //         Vue.axios.post(this.url,  {data: this.meeting, _method: 'put'}).then((response) => {
                //
                //             // Vue.axios.post(this.url,  {data: this.meeting, _method: 'put'}).then((response) => {
                //             let d = response.data;
                //             resolve()
                //         });
                //     }));
            }
        },

        meetingName: {
            get: function () {
                try {
                    return this.meeting.name;
                } catch (e) {
                    return ''
                }


            },
            set(v) {
                this.meeting.name = v;
                this.updateMeeting();
                // //
                // let p = new Promise(((resolve, reject) => {
                //     //send to server
                //     let url = routes.meetings.resource(this.meeting.id);
                //     // Vue.axios.put(url,  this.meeting).then((response) => {
                //     // Vue.axios.post(url, this.meeting).then((response) => {
                //         Vue.axios.post(url, {data: this.meeting, _method: 'put'}).then((response) => {
                //         let d = response.data;
                //         resolve()
                //     });
                // }));
            }
        },

        url: function () {
            if (_.isUndefined(this._meeting) || _.isNull(this._meeting)) {
                return routes.meetings.resource();
            }
            return routes.meetings.resource(this.meeting.id);
        }
    },

    // asyncComputed : {
    //   meeting : function(){
    //
    //   }
    // },
    methods: {
        initializeMeeting: function () {
            if (!_.isNull(this.existingMeeting) || _.isUndefined(this.existingMeeting)) {
                this_meeting = this.existingMeeting;
            } else {
                // this.handleClick()
            }


        },

        updateMeeting: function () {
            let p = new Promise(((resolve, reject) => {
                //send to server
                let url = routes.meetings.resource(this.meeting.id);
                // Vue.axios.put(url,  this.meeting).then((response) => {
                // Vue.axios.post(url, this.meeting).then((response) => {
                Vue.axios.post(url, {data: this.meeting, _method: 'put'}).then((response) => {
                    let d = response.data;
                    resolve()
                });
            }));
        },

        handleClick: function () {
            this.showFields = true;


            return new Promise(((resolve, reject) => {
                //send to server
                // let url = routes.motion.resource();
                Vue.axios.post(this.url).then((response) => {
                    let d = response.data;
                    this.meeting = new Meeting(d.id, d.name, d.date);
                    resolve()

                });
            }));
        }

    }
}

</script>

<style scoped>

</style>
