
<script>
import ButtonParent from "../../parents/button-parent";
import MotionMixin from "../../../mixins/motionStoreMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
export default {
    name: "clear-draft-motion-button",
    components: {},

    extends : ButtonParent,

    props: [],

    mixins: [ MotionMixin, ],

    data: function () {
        return {
            // label : "Clear draft motion",
            styling : " btn-warning ",
            //dev added in VOT-288 to get rid of warning that icon not defined during render
            icon: ''
        }
    },


    computed: {
    // asyncComputed: {
        label : function(){
            if(isReadyToRock(this.draftMotion) && this.draftMotion.type === 'proposition') return  "Clear draft proposition";
            return  "Clear draft motion";
        },
        draftMotion: function () {
            if (isReadyToRock(this.motion) && this.editMode) return this.motion;

            return this.$store.getters.getDraftMotion;
        },
    },


    methods: {
        handleClick : function(){
            this.$store.commit('clearDraftMotion');
            //hide the dialog

        }
    }

}
</script>

<style scoped>

</style>
