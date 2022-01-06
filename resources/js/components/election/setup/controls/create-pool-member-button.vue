
<script>

import buttonParent from "../../../parents/button-parent";
import MeetingMixin from "../../../../mixins/meetingMixin";
import ModeMixin from "../../../../mixins/modeMixin";

export default {
    name: "create-pool-member-button",
    extends: buttonParent,
    props: [],


    mixins: [MeetingMixin, ModeMixin],

    data: function () {
        return {
            label: "Add someone to pool",
            styling: 'btn-info',
        }
    },


    methods: {
        handleClick: function () {
            window.console.log('Create pool member clicked');
            // this.setElection();
            this.$store.dispatch('createOffice', this.meeting);
        }
    }

}
</script>

<style scoped>

</style>
