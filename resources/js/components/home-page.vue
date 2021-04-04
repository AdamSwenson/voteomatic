<template>

    <div class="home-page ">

        <meetings-card></meetings-card>
<!--        <div class="card-header">-->
        <!--            <div class="h3 card-title">Meetings</div>-->
        <!--        </div>-->

        <!--        <div class="card-body">-->


        <!--        </div>-->


    </div>


</template>

<script>

import {isReadyToRock} from "../utilities/readiness.utilities";
import MeetingsCard from "./meetings/meetings-card";
import meetingMixin from "../mixins/meetingMixin";
import urlMixin from "../mixins/urlMixin";

/**
 * This is the internal home page. Only will be accessed if logging in directly
 * from a link which doesn't send to a meeting
 */
export default {
    name: "home-page",
    components: {MeetingsCard},
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
