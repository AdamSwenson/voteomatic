<template>
    <div class="meeting-setup-card card">

        <div class="non-chair" v-if="! isChair">
            <meeting-display-card></meeting-display-card>
        </div>

        <div class="chair" v-else>

            <event-edit-card v-if="isInEventEditingMode"></event-edit-card>
            <meeting-display-card v-else></meeting-display-card>

        </div>
    </div>


    <!--        <div class="card select-meetings">-->
    <!--            <div class="card-header">-->
    <!--                <h4 class="card-title">Select meeting </h4>-->
    <!--            </div>-->

    <!--            <div class="card-body ">-->
    <!--            </div>-->
    <!--        </div>-->


    <!--        <div class="card chair-controls">-->
    <!--            <div class="controls-area card-header">-->

    <!--                <div class="card-text text-end">-->
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


</template>

<script>

import * as routes from "../../routes";
import Meeting from '../../models/Meeting';
import MeetingMixin from '../../mixins/meetingMixin';
import ChairMixin from '../../mixins/chairMixin';
import Payload from "../../models/Payload";
import MeetingsCard from "../deprecated/meetings-card";
import MeetingDisplayCard from "./meeting-display-card";
import RosterCard from "../common/participants-list-card";
import MeetingEditControls from "./meeting-edit-controls";
import ModeMixin from "../../mixins/modeMixin";
import EventEditCard from "../controls/event-edit-card";

export default {
    name: "meeting-setup-card",

    components: {EventEditCard, MeetingEditControls, RosterCard,  MeetingDisplayCard, MeetingsCard},

    props: ['existingMeeting'],

    mixins: [MeetingMixin, ChairMixin, ModeMixin],

    data: function () {
        return {}
    },


    asyncComputed: {},


    computed: {},


    methods: {},


}

</script>

<style scoped>

</style>
