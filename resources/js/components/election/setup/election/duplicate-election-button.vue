<script>
import buttonParent from "../../../parents/button-parent";
import ModeMixin from "../../../../mixins/modeMixin";
import MeetingMixin from "../../../../mixins/meetingMixin";

export default {
    name: "duplicate-election-button",
    extends: buttonParent,
    props: [],

    mixins: [MeetingMixin, ModeMixin],

    data: function () {
        return {
            label: "Duplicate election",
            styling: 'btn-primary',
            icon: 'bi-plus'
        }
    },


    methods: {
        handleClick: function () {
            window.console.log('Duplicate election clicked');
            this.$store.dispatch('duplicateElection', this.meeting );
        }
    }

}
</script>

<style scoped>

</style>
