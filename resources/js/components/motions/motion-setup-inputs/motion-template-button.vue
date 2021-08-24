
<script>
import Payload from "../../../models/Payload";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionMixin from "../../../mixins/motionStoreMixin";

import motionObjectMixin from "../../../mixins/motionObjectMixin";
import RoutingMixin from "../../routingMixin";
import * as routes from "../../../routes";
import ButtonParent from "../../parents/button-parent";


export default {
    name: "motion-template-button",
    components: {},
    extends : ButtonParent,
    props: ["template"],

    mixins: [MeetingMixin, MotionMixin, motionObjectMixin, RoutingMixin],

    data: function () {
        return {
           clicked : false
        }
    },

    computed: {

        label: function () {
            return this.template.name;
        },

        /**
         * Controls whether the spinner is shown
         * @returns {boolean}
         */
        isWorking : function (){
            return this.clicked;
        },

        styling : function(){
            if( this.clicked) return " btn-info motion-template-button";

            return " btn-outline-info motion-template-button";
        }

    },
    methods: {

        makeMain : function(){
            this.$store.dispatch('createMotionFromTemplate', this.template);

        },

        makeSubsidiary : function(){

            let payload = {
                meetingId: this.meeting.id,
                applies_to: this.motion.id,
                content: this.localText,
                type: this.template.type,
                requires: this.template.requires
            };

            payload = _.merge(payload, this.template);

            let p = this.$store.dispatch('createSubsidiaryMotion', payload);
            p.then(() => {

            });
        },

        handleClick: function () {
            //Change the styling and show a spinner
            this.clicked = true;

            let subsidiaryTypes = ['subsidiary', 'procedural-subsidiary',];
            if(subsidiaryTypes.indexOf(this.template.type) > -1 ){
                this.makeSubsidiary();
            }else{
                this.makeMain();
            }

        }
    }

}
</script>

<style scoped>
.motion-template-button {
    margin-right: 1em;
    margin-top: 1em;
}

</style>
