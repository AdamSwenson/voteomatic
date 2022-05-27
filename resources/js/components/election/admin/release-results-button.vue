
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
            if(! isReadyToRock(this.meeting)) return ' btn-outline-warning ';

            if(this.meeting.isComplete && !this.meeting.isVotingAvailable && !this.meeting.isResultsAvailable) return ' btn-warning ';
 return 'btn-outline-warning';
        }
    },

    computed: {},

    methods: {


        handleClick: function () {
            this.$store.dispatch('releaseElectionResults', this.meeting);
        }
    }

}
</script>

<style scoped>

</style>
