<template>

    <div id="meeting-home-card" class="meeting-home card router-tab-touching-card">
            <div class="card-body">

                <motions-card></motions-card>

            </div>
        </div>


<!--    </div>-->


</template>

<script>
import MotionsCard from "../motions/motions-card";
import MeetingMixin from '../../mixins/meetingMixin';
// import RezzieViewHome from "../rezzie-view/pmode-home";
// import TextStylers from "../rezzie-view/text-stylers/text-stylers";

export default {
    name: "meeting-home",
    components: { MotionsCard},
    mixins: [MeetingMixin],

    computed: {

    //     meetingName: function () {
    //         if (_.isUndefined(this.meeting) || _.isNull(this.meeting)) return '';
    //
    //         return this.meeting.name;
    //     },
    //
    //     meetingDate: function () {
    //         if (_.isUndefined(this.meeting) || _.isNull(this.meeting)) return '';
    //
    //         return this.meeting.readableDate();
    //     },
    }
}
</script>

<style scoped>
/*#meeting-home-card {*/
/*    !*Fix annoying gap with navbar*!*/
/*    --bs-card-border-radius: 0;*/
/*}*/
</style>
