<script>
import buttonParent from "../../../parents/button-parent";
import MeetingMixin from "../../../../mixins/meetingMixin";
import ModeMixin from "../../../../mixins/modeMixin";

export default {
    name: "create-office-button",
    extends: buttonParent,
    props: [],


    mixins: [MeetingMixin, ModeMixin],

    data: function () {
        return {
            label: "New Office",
            styling: 'btn-info',
        }
    },


    methods: {
        handleClick: function () {
            window.console.log('Create office clicked');
            // this.setElection();
            this.$store.dispatch('createOffice', this.meeting);
        }
    }

}
</script>

<style scoped>

</style>
