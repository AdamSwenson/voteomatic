
<script>
import ButtonParent from "../../parents/button-parent";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MeetingMixin from "../../../mixins/meetingMixin";

export default {
    name: "end-election-button",
    extends : ButtonParent,
    props: [],
    mixins: [MeetingMixin],

    data: function () {
        return {
            label : 'End voting'
        }
    },

    asyncComputed: {
        styling: function(){
            if(! isReadyToRock(this.meeting)) return ' btn-danger ';
            if(!this.meeting.isVotingAvailable && this.meeting.isComplete) return 'btn-outline-danger';
            return ' btn-danger ';
        }
    },


    methods: {


        handleClick: function () {
this.$store.dispatch('closeElectionVoting', this.meeting);
        }
    }

}
</script>

<style scoped>

</style>
