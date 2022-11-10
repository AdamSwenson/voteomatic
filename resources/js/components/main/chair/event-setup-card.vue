<template>

    <div class="event-setup-card card router-tab-touching-card">

        <div class="controls-area card-header">
            <setup-controls></setup-controls>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <meeting-setup-card v-if="isMeeting"></meeting-setup-card>

                    <election-setup-card v-if="isElection"></election-setup-card>
                </div>
                <div class="col-lg-6">
                    <!--        <div class="card-body">-->
                    <settings-area></settings-area>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <participants-list-card></participants-list-card>
                </div>


                <div class="col-lg-6">
                    <event-list-card></event-list-card>
                </div>
            </div>

        </div>

    </div>


</template>

<script>

import ModeMixin from "../../../mixins/modeMixin";
import SetupControls from "../../controls/setup-controls";
import ElectionSetupCard from "../../election/setup/election/election-setup-card";
import EventListCard from "../../common/event-list-card";
import MeetingSetupCard from "../../meetings/meeting-setup-card";
import ParticipantsListCard from "../../common/participants-list-card";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import SettingsArea from "../../settings/settings-area";

/**
 * Displays the setup page for either meeting or election
 * depending on the mode.
 */
export default {
    name: "event-setup-card",

    components: {
        SettingsArea,
        ParticipantsListCard,
        MeetingSetupCard, EventListCard, ElectionSetupCard, SetupControls
    },

    props: [],

    mixins: [ModeMixin],


    data: function () {
        return {}
    },

    beforeRouteEnter(to, from, next) {
        next(vm => {
            // window.console.log('loading events');
            // access to component instance via `vm`
            // vm.loadEvents();
        })
    },

    asyncComputed: {},

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
