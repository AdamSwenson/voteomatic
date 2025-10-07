
<script>
import MeetingMixin from "../../../mixins/meetingMixin";
import ButtonParent from "../../parents/button-parent";

export default {
    name: "create-motion-button",

    extends : ButtonParent,

    props: [],

    mixins: [MeetingMixin],

    data: function () {
        return {
            label : "Create main motion",
            styling : "  btn-outline-primary me-2",
            //dev added in VOT-288 to get rid of warning that icon not defined during render
            icon: ''

        }
    },

    computed: {},

    methods: {
        initializeMotion: function () {

            let p = this.$store.dispatch('initializeDraftMainMotion');
            let me = this;
            p.then(() => {
                me.$emit('create-motion-clicked');
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
