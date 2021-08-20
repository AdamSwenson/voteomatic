
<script>

import MeetingMixin from '../../../mixins/meetingMixin'
import DeleteModalParent from "../../controls/delete-modal-parent";
import ModeMixin from "../../../mixins/modeMixin";

/**
 * Note, this will require that the delete-meeting-button is
 * included elsewhere on the page. They are linked via  bootstrap
 * using the data-dismiss=modal attribute. They are not linked
 * by vue or vuex events.
 */
export default {
    name: "delete-meeting-modal",
    extends: DeleteModalParent,
    props: [],

    mixins: [MeetingMixin, ModeMixin],

    data: function () {
        return {
            // type: 'meeting',
            // subsidiaryType : 'office'
        }
    },

    computed: {
        // type: function () {
        //     return this.meeting.type;
        // },
        //
        // typeCapitalized: function () {
        //     return this.type.toUpperCase();
        // },


    },

    methods: {
        handleClick: function () {

            let me = this;

            //First we create and store a new meeting from the
            //provided template
            let p = this.$store.dispatch('deleteMeeting', me.meeting)
                .then(function () {
                });
        }

    }


}

</script>

<style scoped>

</style>
