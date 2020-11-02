<template>
    <div class="meeting-setup card">
        <div class="card-header">
            <h1>Create and edit meetings</h1>
        </div>

        <div class="card-body edit-meeting">
            <!--            <div class="card-text">-->


            <div class="setup-fields" v-if="showFields">

                <label for="meeting-name">Meeting name</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="meeting-name" v-model="meetingName">
                </div>

                <label for="meeting-date">Meeting date</label>
                <div class="input-group mb-3">
                    <input type="date" class="form-control" id="meeting-date" v-model="meetingDate">
                </div>

            </div>

            <div class="card-text">
                <button class="btn btn-primary"
                        v-on:click="handleClick"
                >Create new meeting
                </button>
            </div>

        </div>

        <div class="card-body select-meetings">
            <h4 class="card-title">Select meeting </h4>
<!--            <p><strong>ToDo</strong></p>-->

            <meetings-card></meetings-card>
            <!--                    <h4 class="card-title">Manage meeting access</h4>-->
            <!--                    <p><strong>ToDo</strong></p>-->

        </div>


        <div class="roster-area card-body">
            <h4 class="card-title">Meeting roster</h4>

            <p><strong>ToDo</strong></p>
        </div>


    </div>


</template>

<script>

import * as routes from "../../../routes";
import Meeting from '../../../models/Meeting';
import MeetingMixin from '../../storeMixins/meetingMixin';
import Payload from "../../../models/Payload";
import MeetingsCard from "../../navigation/meetings-card";

export default {
    name: "meeting-setup",
    components: {MeetingsCard},
    props: ['existingMeeting'],

    mixins: [MeetingMixin],

    data: function () {
        return {
            showFields: true,
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
                // this.meeting.date = v;
                let p = Payload.factory({
                        'object': this.meeting,
                        'updateProp': 'date',
                        'updateVal': v
                    }
                );
                this.$store.dispatch('updateMeeting', p);

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
                let p = Payload.factory({
                        'object': this.meeting,
                        'updateProp': 'name',
                        'updateVal': v
                    }
                );
                window.console.log(p);
                this.$store.dispatch('updateMeeting', p);
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


        handleClick: function () {
            let me = this;
            let p = this.$store.dispatch('createMeeting');
            p.then(function () {
                me.showFields = true;

            })

        }

    }
}

</script>

<style scoped>

</style>
