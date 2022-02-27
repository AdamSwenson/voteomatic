
<script>
import ButtonParent from '../../parents/button-parent';
import MeetingMixin from "../../../mixins/meetingMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "start-election-button",

    extends : ButtonParent,
    mixins: [MeetingMixin],

    props: [],



    data: function () {
        return {

            label: "Enable voting",

            //isWorking : false
        }
    },

    asyncComputed: {
        styling: function(){
          if(! isReadyToRock(this.meeting)) return ' btn-success ';
          if(this.meeting.isVotingAvailable) return 'btn-outline-success';
        return ' btn-success ';
        }
    },

    computed: {},

    methods: {

        handleClick: function () {

            this.$store.dispatch('enableElectionVoting', this.meeting);
        }
    }

}
</script>

<style scoped>

</style>
