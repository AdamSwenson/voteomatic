<template>
    <div class="meeting-setup card">

        <meeting-edit-card v-if="isChair"></meeting-edit-card>

        <meeting-display-card></meeting-display-card>

        <meetings-card></meetings-card>

        <roster-card v-if="isChair" ></roster-card>

<!--        <div class="card select-meetings">-->
<!--            <div class="card-header">-->
<!--                <h4 class="card-title">Select meeting </h4>-->
<!--            </div>-->

<!--            <div class="card-body ">-->
<!--            </div>-->
<!--        </div>-->



        <!--        <div class="card chair-controls">-->
        <!--            <div class="controls-area card-header">-->

        <!--                <div class="card-text text-right">-->
        <!--                    <button class="btn btn-primary"-->
        <!--                            v-on:click="handleClick"-->
        <!--                    >Create new meeting-->
        <!--                    </button>-->
        <!--                </div>-->

        <!--                <button class="btn btn-outline-danger"-->
        <!--                        v-if="isChair"-->
        <!--                        v-on:click="handleEditButtonClick"-->
        <!--                >Edit current meeting-->
        <!--                </button>-->

        <!--            </div>-->

        <!--            <div class="card-header">-->

        <!--                <h1 class="card-title">{{ editAreaTitle }}</h1>-->

        <!--            </div>-->

        <!--            <div class="card-body edit-meeting"-->
        <!--                 v-if="showArea === 'edit' || showArea === 'create'"-->
        <!--            >-->

        <!--                <div class="setup-fields" v-if="showFields">-->

        <!--                    <label for="meeting-name">Meeting name</label>-->
        <!--                    <div class="input-group mb-3">-->
        <!--                        <input type="text" class="form-control" id="meeting-name" v-model="meetingName">-->
        <!--                    </div>-->

        <!--                    <label for="meeting-date">Meeting date</label>-->
        <!--                    <div class="input-group mb-3">-->
        <!--                        <input type="date" class="form-control" id="meeting-date" v-model="meetingDate">-->
        <!--                    </div>-->

        <!--                    <p class="text-muted">Your entries are automatically saved on the-->
        <!--                        server as you type. You don't need to click anything when you are done.</p>-->
        <!--                    <p class="text-muted">If you do not type anything, there will be a blank meeting. Use the delete-->
        <!--                        button to fix this.</p>-->

        <!--                </div>-->
        <!--            </div>-->

        <!--            <div class="card meeting-display">-->
        <!--            <div class="card-header">-->

        <!--                <h1 class="card-title">The current meeting</h1>-->

        <!--            </div>-->


        <!--        </div>&ndash;&gt;-->
        <!--    </div>-->

<!--        <div class="card select-meetings">-->
<!--            <div class="card-header">-->
<!--                <h4 class="card-title">Select meeting </h4>-->
<!--            </div>-->

<!--            <div class="card-body ">-->
<!--                <meetings-card></meetings-card>-->
<!--            </div>-->
<!--        </div>-->

<!--        <div class="roster-area card">-->
<!--            <div class="card-header">-->
<!--                <h4 class="card-title">Meeting roster</h4>-->
<!--            </div>-->

<!--            <div class="card-body">-->
<!--                <p><strong>ToDo</strong></p>-->
<!--            </div>-->
<!--        </div>-->


    </div>


</template>

<script>

import * as routes from "../../../routes";
import Meeting from '../../../models/Meeting';
import MeetingMixin from '../../../mixins/meetingMixin';
import ChairMixin from '../../../mixins/chairMixin';
import Payload from "../../../models/Payload";
import MeetingsCard from "../../meetings/meetings-card";
import MeetingDisplayCard from "../../meetings/meeting-display-card";
import MeetingEditCard from "../../meetings/meeting-edit-card";
import RosterCard from "../../meetings/roster-card";

export default {
    name: "meeting-setup",
    components: {RosterCard, MeetingEditCard, MeetingDisplayCard, MeetingsCard},
    props: ['existingMeeting'],

    mixins: [MeetingMixin, ChairMixin],

    data: function () {
        return {
            // showFields: true,
            //
            // /**
            //  * What set of fields to show
            //  * Values:
            //  *      false
            //  *      edit
            //  *      create
            //  */
            // showArea: false,

            // linkBase: "https://voteomatic.com/lti-entry/"
        }
    },


    asyncComputed: {

        // /**
        //  * The link that the user will enter into
        //  * canvas
        //  */
        // meetingLink: {
        //     get: function () {
        //         return this.linkBase + this.meeting.id;
        //     },
        //     default: false
        // },

    },


    computed: {
        // editAreaTitle: function () {
        //     switch (this.showArea) {
        //         case 'create':
        //             return "Create meeting";
        //             break;
        //         case 'edit':
        //             return 'Edit meeting';
        //             break
        //         default:
        //             return '';
        //     }
        // },
        //
        // meetingDate: {
        //     get: function () {
        //         try {
        //             return this.meeting.date;
        //         } catch (e) {
        //             return ''
        //         }
        //     },
        //
        //     set(v) {
        //         // this.meeting.date = v;
        //         let p = Payload.factory({
        //                 'object': this.meeting,
        //                 'updateProp': 'date',
        //                 'updateVal': v
        //             }
        //         );
        //         this.$store.dispatch('updateMeeting', p);
        //     }
        // },
        //
        //
        // meetingName: {
        //     get: function () {
        //         try {
        //             return this.meeting.name;
        //         } catch (e) {
        //             return ''
        //         }
        //
        //
        //     },
        //     set(v) {
        //         let p = Payload.factory({
        //                 'object': this.meeting,
        //                 'updateProp': 'name',
        //                 'updateVal': v
        //             }
        //         );
        //         window.console.log(p);
        //         this.$store.dispatch('updateMeeting', p);
        //     }
        // },

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

        //
        // handleClick: function () {
        //     let me = this;
        //     let p = this.$store.dispatch('createMeeting');
        //     p.then(function () {
        //         me.showFields = true;
        //         me.showArea = 'create';
        //     })
        // },
        //
        //
        // handleEditButtonClick: function () {
        //     this.showArea = 'edit';
        // }

    }
}

</script>

<style scoped>

</style>
