<script>
import DeleteButtonParent from "../../../../controls/delete-button-parent";
import MeetingMixin from "../../../../../mixins/meetingMixin";
import ModeMixin from "../../../../../mixins/modeMixin";

export default {
    name: "delete-office-button",
    extends: DeleteButtonParent,
    props: [],

    mixins: [MeetingMixin, ModeMixin],
    data: function () {
        return {
            label: 'Delete office',
            icon : 'bi-x'
          }
    },
computed : {
    modalId : function(){
        return "deleteOfficeModal"
    },

},


}
</script>

<style scoped>

</style>
