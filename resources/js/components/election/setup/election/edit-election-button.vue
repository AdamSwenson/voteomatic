
<script>
import ButtonParent from "../../../parents/button-parent";
import MeetingMixin from "../../../../mixins/meetingMixin";
import ModeMixin from "../../../../mixins/modeMixin";
export default {
    name: "edit-election-button",
    extends : ButtonParent,
    props: [],

    mixins: [MeetingMixin, ModeMixin],



    data: function () {
        return {
            label : "Edit election",
            styling: 'btn-warning',
            icon : 'bi-pencil'
        }
    },


    computed: {},

    methods: {

        handleClick: function () {
            this.setShowArea('edit');
        }
    }

}
</script>

<style scoped>

</style>
