<template>

    <div class="card">

        <div class="card-header">
            <h4 class="card-title">{{ title }}</h4>
        </div>

        <div class="card-body">

            <ul class="list-group list-group-flush"
                v-if="isReady">

                <meeting-select-button v-for="m in displayedEvents"
                                       :meeting="m"
                                       :key="m.id">
                </meeting-select-button>

            </ul>

        </div>

    </div>


</template>

<script>
import MeetingSelectButton from "../meetings/meeting-list-item";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import MeetingMixin from "../../mixins/meetingMixin";
import ChairMixin from "../../mixins/chairMixin";
import ModeMixin from "../../mixins/modeMixin";
// import MeetingMixin from '../storeMixins/meetingMixin';
// import MeetingMixin from '../storeMixins/meetingMixin';

export default {
    name: "event-list-card",
    components: {MeetingSelectButton},


    mixins: [MeetingMixin, ChairMixin, ModeMixin],

    // mixins : [MeetingMixin],
    data: function () {
        return {
            isReady: false
        }
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            window.console.log('loading events');
            // access to component instance via `vm`
            vm.loadEvents();
        })
    },

    asyncComputed: {
        events: function () {
            let m = this.$store.getters.getStoredMeetings;
            if (!isReadyToRock(m)) return [];
            return m;
        },

        displayedEvents: function () {
            if (this.isElection) return this.elections;
            if (this.isMeeting) return this.meetings;
        },

        elections: function () {
            return _.filter(this.events, (event) => {
                return event.type === 'election';
            });
        },

        /**
         * What the user perceives as meetings
         * @returns {Array|*}
         */
        meetings: function () {
            return _.filter(this.events, (event) => {
                return event.type === 'meeting';
            });
        },

        title: function () {
            if (this.isElection) return 'Elections';
            if (this.isMeeting) return 'Meetings';
        }
    },

    methods: {
        setMeeting: function (meeting) {
            this.$store.commit('setMeeting', meeting);
        },

        loadEvents: function () {
            let me = this;
            this.$store.dispatch('loadAllEvents').then(() => {
                // me.$store.dispatch('loadAllElections').then(() => {
                me.isReady = true;
                // });

            });


        }

    },

    mounted() {
        this.loadEvents();
    }
}
</script>

<style scoped>

</style>
