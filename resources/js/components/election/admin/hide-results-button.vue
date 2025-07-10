
<script>
import ButtonParent from "../../parents/button-parent";
import MeetingMixin from "../../../mixins/meetingMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "hide-results-button",
    extends : ButtonParent,
    props: [],

    mixins: [MeetingMixin],


    data: function () {
        return {
            label : 'Hide results',
            info: "Prevents anyone except the chair or administrator from viewing results"
        }
    },


    computed: {
    // asyncComputed: {
        styling: function(){
            if(! isReadyToRock(this.meeting)) return ' btn-outline-warning ';

            if(this.meeting.isComplete && !this.meeting.isVotingAvailable && this.meeting.isResultsAvailable) return ' btn-warning ';
            return 'btn-outline-warning';


        }
    },


    methods: {


        handleClick: function () {
            this.$store.dispatch('hideElectionResults', this.meeting);

        }
    }

}
</script>

<style scoped>

</style>
