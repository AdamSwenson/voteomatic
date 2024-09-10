
<script>

import MeetingMixin from '../../../mixins/meetingMixin'
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import ModeMixin from "../../../mixins/modeMixin";
import DeleteButtonParent from "../../controls/delete-button-parent";

export default {
    name: "delete-meeting-button",
    extends: DeleteButtonParent,
    props: [],
    mixins: [MeetingMixin, ModeMixin],

    data: function () {
        return {
            label: 'Delete meeting',
            icon : 'bi-x'

        }
    },

    computed: {

        // label : function(){
        //     if(this.isMeeting) return 'Delete meeting';
        //     // if(this.isElection) return 'Delete election';
        // },

        // typeCapitalized : function(){
        //     return this.type.toUpperCase();
        // },
        //

    },
    methods: {
        // handleClick: function () {

    //
    //         let me = this;
    //
    //         //First we create and store a new meeting from the
    //         //provided template
    //         let p = this.$store.dispatch('deleteMeeting', me.meeting)
    //             .then(function () {
    //             });
    //     }
    }
}
</script>

<style scoped>

</style>
