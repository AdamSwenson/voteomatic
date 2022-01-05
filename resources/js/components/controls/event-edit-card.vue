<template>
    <div class="event-edit-card card">

        <div class="setup-fields">
            <!--            <div class="setup-fields" v-if="showFields">-->
            <!--            <div class="card-header">-->
            <!--                <h4 class="card-title">{{ editAreaTitle }} <span class="text-danger">(Chair only)</span></h4>-->
            <!--            </div>-->

            <div class="card-body edit-event">

                <!--            <div class="card-body edit-event"-->
                <!--                 v-if="showArea === 'edit' || showArea === 'create'"-->
                <!--            >-->

                <label for="event-name">{{ eventTypeCapitalized }} name</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="event-name" v-model="eventName">
                </div>

                <label for="event-date">{{ eventTypeCapitalized }} date <span
                    class="text-secondary">(optional)</span></label>
                <div class="input-group mb-3">
                    <input type="date" class="form-control" id="event-date" v-model="eventDate">
                </div>

                <entry-instructions></entry-instructions>

                <meeting-url-display></meeting-url-display>

            </div>

        </div>

    </div>
</template>

<script>
import Payload from "../../models/Payload";
import * as routes from "../../routes";
import MeetingMixin from '../../mixins/meetingMixin';
import {isReadyToRock} from "../../utilities/readiness.utilities";
import ModeMixin from "../../mixins/modeMixin";
import EntryInstructions from "./entry-instructions";
import MeetingUrlDisplay from "../meetings/meeting-url-display";

export default {
    name: "event-edit-card",
    components: {MeetingUrlDisplay, EntryInstructions},
    // components: {
    // props: ['shouldShowFields'],
// {
//         showFields: {
//             type: Boolean,
//             default: false
//         },
//     },

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


    asyncComputed: {
        showFields: function () {
            return true;
        },

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
            return t + this.eventType;
        },
    },
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


        eventDate: {
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


        eventName: {
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

        // url: function () {
        //     if (_.isUndefined(this._meeting) || _.isNull(this._meeting)) {
        //         return routes.meetings.resource();
        //     }
        //     return routes.meetings.resource(this.meeting.id);
        // }
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


        // handleEditButtonClick: function (v) {
        //     this.showFields = !this.showFields;
        //     this.showArea = v;
        // }
    }

}
</script>

<style scoped>

</style>
