<template>

    <div class="home-page card">
        <div class="card-header">
            <h3 class="header-text">Welcome to the Voteomatic </h3>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col">
                    <logout-button></logout-button>
                </div>
            </div>

        </div>

        <div class="card-body">
            <div class="row">
                <div class="col">
                    <event-list-card event-type="meeting"></event-list-card>
                </div>

                <div class="col">
                    <event-list-card event-type="election"></event-list-card>
                </div>
            </div>

        </div>
    </div>


</template>

<script>

import {isReadyToRock} from "../utilities/readiness.utilities";
import meetingMixin from "../mixins/meetingMixin";
import urlMixin from "../mixins/urlMixin";
import EventListCard from "./common/event-list-card";
import LogoutButton from "./navigation/logout-button";

/**
 * This is the internal home page. Only will be accessed if logging in directly
 * from a link which doesn't send to a meeting
 */
export default {
    name: "home-page",
    components: {LogoutButton, EventListCard,},
    props: [],

    mixins: [meetingMixin, urlMixin],

    data: function () {
        return {}
    },

    asyncComputed: {

        // meetings: function(){
        //     let m = this.$store.getters.getStoredMeetings;
        //     if(isReadyToRock(m)){
        //         return m;
        //     }
        //     return [];
        // }

    },

    watch: {

        meeting: function () {
            let url = this.routeRoot + 'main/' + this.meeting.id
            window.console.log('meeting changed', url);
            window.open(url, '_self');
        }
    },

    computed: {},

    methods: {},

    mounted() {
        // this.$store.dispatch('setMeetingMode');

        // this.$store.dispatch('loadAllMeetings').then(() => {
        //
        // });
    }

}
</script>

<style scoped>

</style>
