<template>
<div class="meeting-setup-card card">

        <event-edit-card></event-edit-card>
</div>

        <!--        <div class="setup-fields" v-if="showFields">-->

<!--        <div class="card-header">-->
<!--            <h4 class="card-title">{{ editAreaTitle }} <span class="text-danger">(Chair only)</span></h4>-->
<!--        </div>-->

<!--        <div class="card-body edit-meeting">-->

<!--            &lt;!&ndash;        <div class="card-body edit-meeting"&ndash;&gt;-->
<!--            &lt;!&ndash;                 v-if="showArea === 'edit' || showArea === 'create'"&ndash;&gt;-->
<!--            &lt;!&ndash;            >&ndash;&gt;-->

<!--            <label for="meeting-name">{{ eventTypeCapitalized }} name</label>-->
<!--            <div class="input-group mb-3">-->
<!--                <input type="text" class="form-control" id="meeting-name" v-model="meetingName">-->
<!--            </div>-->

<!--            <label for="meeting-date">{{ eventTypeCapitalized }} date <span-->
<!--                class="text-secondary">(optional)</span></label>-->
<!--            <div class="input-group mb-3">-->
<!--                <input type="date" class="form-control" id="meeting-date" v-model="meetingDate">-->
<!--            </div>-->

<!--            <entry-instructions></entry-instructions>-->

<!--        </div>-->

        <!--        </div>-->

</template>

<script>
import Payload from "../../models/Payload";
import * as routes from "../../routes";
import MeetingMixin from '../../mixins/meetingMixin';
import DeleteMotionButton from "../motions/motion-setup-inputs/delete-motion-button";
import DeleteMotionModal from "../motions/motion-setup-inputs/delete-motion-modal";
import DeleteMeetingButton from "../meetings/controls/delete-meeting-button";
import DeleteMeetingModal from "../meetings/controls/delete-meeting-modal";
import MeetingEditControls from "../meetings/meeting-edit-controls";
import ElectionEditControls from "../election/setup/election/election-setup-controls";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import ModeMixin from "../../mixins/modeMixin";
import EntryInstructions from "../controls/entry-instructions";
import EventEditCard from "../controls/event-edit-card";
import MeetingsCard from "./meetings-card";

export default {
    components: {
        MeetingsCard,
        EventEditCard,
        EntryInstructions,
        ElectionEditControls,
        MeetingEditControls, DeleteMeetingModal, DeleteMeetingButton, DeleteMotionModal, DeleteMotionButton
    },

    props: [],

    mixins: [MeetingMixin, ModeMixin],


    data: function () {

        return {

            // showFields: isReadyToRock(this.shouldShowFields) ? this.shouldShowFields : false,

            /**
             * What set of fields to show
             * Values:
             *      false
             *      edit
             *      create
             */
            // showArea: 'edit',

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
            let t = '';
            switch (this.showArea) {
                case 'create':
                    t = "Create "; // . this.type;
                    break;
                case 'edit':
                    t = 'Edit '// . this.type;
                    break
                // default:
                //     return '';
            }
            return t + this.type;
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


        // handleClick: function () {
        //     let me = this;
        //     let p = this.$store.dispatch('createMeeting');
        //     p.then(function () {
        //         me.showFields = true;
        //         me.showArea = 'create';
        //     })
        // },


        handleEditButtonClick: function (v) {
            this.showFields = !this.showFields;
            this.showArea = v;
        }
    }

}
</script>

<style scoped>

</style>
