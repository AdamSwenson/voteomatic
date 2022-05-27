<script>
import DeleteButtonParent from "../../../controls/delete-button-parent";
import DeleteModalParent from "../../../controls/delete-modal-parent";
import MeetingMixin from "../../../../mixins/meetingMixin";
import ModeMixin from "../../../../mixins/modeMixin";
import MotionMixin from "../../../../mixins/motionStoreMixin";
import ChairMixin from "../../../../mixins/chairMixin";
import motionObjectMixin from "../../../../mixins/motionObjectMixin";
export default {
    name: "delete-office-modal",
    extends : DeleteModalParent,
    props: [],

    data: function(){
return {
    eventType: 'office',
    subsidiaryType: 'candidate lists'
}; },

    mixins: [MeetingMixin, ModeMixin, MotionMixin, ChairMixin, motionObjectMixin],

    computed: {

        modalId : function(){
            return "deleteOfficeModal"
        },

    },

    methods: {
        handleClick: function () {

            let me = this;

            //First we create and store a new meeting from the
            //provided template
            let p = this.$store.dispatch('deleteOffice', me.motion)
                .then(function () {
                    window.console.log("Office deleted");
                });
        }
    }

}
</script>

<style scoped>

</style>
