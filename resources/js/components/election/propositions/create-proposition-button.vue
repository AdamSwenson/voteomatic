
<script>
import MeetingMixin from "../../../mixins/meetingMixin";
import ButtonParent from "../../parents/button-parent";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";

export default {
    name: "create-proposition-button",

    extends : ButtonParent,

    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],

    data: function () {
        return {
            label : "New proposition",
            styling : "  btn-primary ",
            icon: 'bi-plus'

        }
    },

    computed: {},

    methods: {
        initializeMotion: function () {

            let p = this.$store.dispatch('initializeDraftProposition');
            let me = this;
            p.then(() => {
                //Unset
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
