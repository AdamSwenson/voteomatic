<script>
import buttonParent from "../../parents/button-parent";
import MeetingMixin from "../../../mixins/meetingMixin";

export default {
    name: "create-office-button",
    extends: buttonParent,
    props: [],


    mixins: [MeetingMixin],

    data: function () {
        return {
            label: "New Office",
            styling: 'btn-info',
        }
    },


    methods: {
        handleClick: function () {
            window.console.log('Create office clicked');
            this.$store.dispatch('createOffice', this.meeting);
        }
    }

}
</script>

<style scoped>

</style>
