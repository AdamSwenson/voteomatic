
<script>
import ButtonParent from "../../parents/button-parent";
import MeetingMixin from "../../../mixins/meetingMixin";

export default {
    name: "create-resolution-button",
    extends : ButtonParent,


    mixins: [MeetingMixin],

    data: function () {
        return {
            label : "Create resolution",
            styling : "  btn-outline-primary ",
            //dev added in VOT-288 to get rid of warning that icon not defined during render
            icon: ''

        }
    },

    computed: {},

    methods: {
        initializeMotion: function () {

            let p = this.$store.dispatch('initializeDraftResolution');
            let me = this;
            p.then(() => {
                me.$emit('create-resolution-clicked');
            });
        },


        handleClick: function () {
            this.initializeMotion();

        }
    }


}
</script>

<style scoped>

</style>
