
<script>
import ButtonParent from "../../parents/button-parent";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MeetingMixin from "../../../mixins/meetingMixin";

export default {
    name: "create-meeting-button",
    extends : ButtonParent,
    props: [],
    mixins: [MeetingMixin],

    data: function () {
        return {
            label : 'New meeting'
        }
    },

    asyncComputed: {
        styling: function(){
            return 'btn-primary';
            // if(! isReadyToRock(this.meeting)) return ' btn-danger ';
            // if(!this.meeting.isVotingAvailable && this.meeting.isComplete) return 'btn-outline-danger';
            // return ' btn-danger ';
        }
    },


    methods: {


        handleClick: function () {
this.$store.dispatch('createMeeting');
        }
    }

}
</script>

<style scoped>

</style>
