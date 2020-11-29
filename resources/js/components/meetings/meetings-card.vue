<template>

    <div class="card">

        <div class="card-header">
            <h4 class="card-title">Meetings</h4>
        </div>

        <div class="card-body">

            <ul class="list-group list-group-flush"
            v-if="isReady">

                <meeting-select-button v-for="m in meetings"
                                      :meeting="m"
                                      :key="m.id">

                </meeting-select-button>

            </ul>

        </div>
    </div>


</template>

<script>
import MeetingSelectButton from "./meeting-select-area";
// import MeetingMixin from '../storeMixins/meetingMixin';
// import MeetingMixin from '../storeMixins/meetingMixin';

export default {
    name: "meetings-card",
    components: {MeetingSelectButton},
    // mixins : [MeetingMixin],
    data : function (){
        return {
            isReady : false
        }
    },
    asyncComputed: {
        meetings: function () {
            let m = this.$store.getters.getStoredMeetings;
        if(_.isUndefined(m)) return [];
        return m;
        }
    },

    methods: {
        setMeeting: function (meeting) {
            this.$store.commit('setMeeting', meeting);
        }

    },

    mounted() {
        let p = this.$store.dispatch('loadAllMeetings' );
        let me = this;
        p.then(function(){
            me.isReady = true;
        });
    }
}
</script>

<style scoped>

</style>
