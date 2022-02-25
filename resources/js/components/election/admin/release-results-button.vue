
<script>
import ButtonParent from "../../parents/button-parent";
import MeetingMixin from "../../../mixins/meetingMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "release-results-button",
    extends : ButtonParent,
    props: [],

    mixins: [MeetingMixin],


    data: function () {
        return {
            label : 'Release results'
        }
    },

    asyncComputed: {
        styling: function(){
            if(! isReadyToRock(this.meeting)) return ' btn-warning ';
            if(!this.meeting.isVotingAvailable && this.meeting.isComplete) return 'btn-outline-warning';
            return ' btn-warning ';
        }
    },

    computed: {},

    methods: {


        handleClick: function () {

        }
    }

}
</script>

<style scoped>

</style>
