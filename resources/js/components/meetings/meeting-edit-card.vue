<template>
    <div class="meeting-edit-card card">
        <div class="controls-area card-header">

            <button class="btn btn-primary"
                    v-on:click="handleClick"
            >Create new meeting
            </button>

            <button class="btn btn-warning"
                    v-on:click="handleEditButtonClick"
            >Edit current meeting
            </button>

            <delete-meeting-button></delete-meeting-button>
            <delete-meeting-modal></delete-meeting-modal>

        </div>

        <div class="setup-fields" v-if="showFields">

            <div class="card-header">
                <h4 class="card-title">{{ editAreaTitle }} <span class="text-danger">(Chair only)</span></h4>
            </div>

            <div class="card-body edit-meeting"
                 v-if="showArea === 'edit' || showArea === 'create'"
            >

                <label for="meeting-name">Meeting name</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="meeting-name" v-model="meetingName">
                </div>

                <label for="meeting-date">Meeting date</label>
                <div class="input-group mb-3">
                    <input type="date" class="form-control" id="meeting-date" v-model="meetingDate">
                </div>

                <p class="text-muted">Your entries are automatically saved on the
                    server as you type. You don't need to click anything when you are done.</p>
                <p class="text-muted">If you do not type anything, there will be a blank meeting. Use the delete
                    button below to fix this.</p>

            </div>

        </div>

    </div>
</template>

<script>
import Payload from "../../models/Payload";
import * as routes from "../../routes";
import MeetingMixin from '../../mixins/meetingMixin';
import DeleteMotionButton from "../motions/motion-setup-inputs/delete-motion-button";
import DeleteMotionModal from "../motions/motion-setup-inputs/delete-motion-modal";
import DeleteMeetingButton from "./delete-meeting-button";
import DeleteMeetingModal from "./delete-meeting-modal";

export default {
    name: "meeting-edit-card",
    components: {DeleteMeetingModal, DeleteMeetingButton, DeleteMotionModal, DeleteMotionButton},
    props: [],

    mixins: [MeetingMixin],

    data: function () {

        return {
            showFields: false,

            /**
             * What set of fields to show
             * Values:
             *      false
             *      edit
             *      create
             */
            showArea: false,

        }
    },


    // asyncComputed: {
    //
    //     /**
    //      * The link that the user will enter into
    //      * canvas
    //      */
    //     meetingLink: {
    //         get: function () {
    //             return this.linkBase + this.meeting.id;
    //         },
    //         default: false
    //     },
    //
    // },


    computed: {
        editAreaTitle: function () {
            switch (this.showArea) {
                case 'create':
                    return "Create meeting";
                    break;
                case 'edit':
                    return 'Edit meeting';
                    break
                default:
                    return '';
            }
        },

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


    methods: {


        handleClick: function () {
            let me = this;
            let p = this.$store.dispatch('createMeeting');
            p.then(function () {
                me.showFields = true;
                me.showArea = 'create';
            })
        },


        handleEditButtonClick: function () {
            this.showArea = 'edit';
        }
    }

}
</script>

<style scoped>

</style>
