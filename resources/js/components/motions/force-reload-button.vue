
<script>
import ButtonParent from "../parents/button-parent.vue";
import MeetingMixin from "../../mixins/meetingMixin";

export default {
    name: "force-reload-button",

    extends: ButtonParent,
    props: [],
    mixins: [MeetingMixin,],

    data: function () {
        return {
            label: 'Force all users to reload',
            icon: ''
        }
    },

    computed:{
        // asyncComputed: {
        styling: function () {
            return 'btn-danger text-light';

        }
    },


    methods: {


        handleClick: function () {
            this.$store.dispatch('forceUsersToReload');
        }
    }

}
</script>


<style scoped>

</style>
