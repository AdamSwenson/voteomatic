<script>
import DeleteButtonParent from "../../../controls/delete-button-parent";
import MeetingMixin from "../../../../mixins/meetingMixin";
import ModeMixin from "../../../../mixins/modeMixin";

export default {
    name: "delete-election-button",
    extends: DeleteButtonParent,
    props: [],

    mixins: [MeetingMixin, ModeMixin],
    data: function () {
        return {
            label: 'Delete election',
          }
    },

    asyncComputed: {},

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
