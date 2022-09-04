<script>
import DeleteButtonParent from "../../../controls/delete-button-parent";
import DeleteModalParent from "../../../controls/delete-modal-parent";
import MeetingMixin from "../../../../mixins/meetingMixin";
import ModeMixin from "../../../../mixins/modeMixin";
export default {
    name: "delete-election-modal",
    extends : DeleteModalParent,
    props: [],

    mixins: [MeetingMixin, ModeMixin],

    data: function () {
        return {
            // type: 'election',
            // subsidiaryType : 'office'
        }
    },


    computed: {

    },

    methods: {
        handleClick: function () {

            let me = this;

            //First we create and store a new meeting from the
            //provided template
            let p = this.$store.dispatch('deleteElection', me.meeting)
                .then(function () {
                });
        }
    }

}
</script>

<style scoped>

</style>
