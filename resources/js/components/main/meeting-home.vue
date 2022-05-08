<template>

    <div class="meeting-home">

        <div class="card">
            <div class="card-body">

<!--                <h4 class="card-title">{{ meetingName }}</h4>-->

<!--                <h6 class="card-subtitle">{{meetingDate}}</h6>-->

                <motions-card></motions-card>

            </div>
        </div>

        <text-stylers></text-stylers>

        <rezzie-view-home></rezzie-view-home>


    </div>


</template>

<script>
import MotionsCard from "../motions/motions-card";
import MeetingMixin from '../../mixins/meetingMixin';
import RezzieViewHome from "../rezzie-view/rezzie-view-home";
import TextStylers from "../rezzie-view/text-stylers/text-stylers";

export default {
    name: "meeting-home",
    components: {TextStylers, RezzieViewHome, MotionsCard},
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

</style>
