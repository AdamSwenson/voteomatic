<template>

    <div class="home-page ">
<event-list-card></event-list-card>
<!--        <meetings-card></meetings-card>-->
<!--        <div class="card-header">-->
        <!--            <div class="h3 card-title">Meetings</div>-->
        <!--        </div>-->

        <!--        <div class="card-body">-->


        <!--        </div>-->


    </div>


</template>

<script>

import {isReadyToRock} from "../utilities/readiness.utilities";
import meetingMixin from "../mixins/meetingMixin";
import urlMixin from "../mixins/urlMixin";
import EventListCard from "./common/event-list-card";

/**
 * This is the internal home page. Only will be accessed if logging in directly
 * from a link which doesn't send to a meeting
 */
export default {
    name: "home-page",
    components: {EventListCard,},
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

    watch : {

        meeting: function(){
            let url = this.routeRoot + 'main/' + this.meeting.id
            window.console.log('meeting changed' , url);
            window.open(url, '_self');
        }
    },

    computed: {},

    methods: {},

    mounted() {

        // this.$store.dispatch('loadAllMeetings').then(() => {
        //
        // });
    }

}
</script>

<style scoped>

</style>
