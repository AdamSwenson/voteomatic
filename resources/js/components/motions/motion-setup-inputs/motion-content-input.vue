<template>
    <div class="form-group">
        <label for="motion-content">It is moved that </label>
        <textarea id="motion-content"
                  class="form-control"
                  rows="3"
                  v-model="content"
                  v-bind:placeholder="placeholders.content"
        ></textarea>
    </div>


</template>

<script>
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionMixin from "../../../mixins/motionStoreMixin";
import Payload from "../../../models/Payload";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
name: "motion-content-input",
    mixins: [MeetingMixin], //, MotionMixin],


    props : ['motion', 'editMode'],


data : function(){
    return {
        placeholders: {
            content: "that tacos be declared the official food of this body.",
            description: "(This is currently unused)"
        }
    }
},

computed : {
    content: {
        get: function () {
            if (_.isUndefined(this.motion) || _.isNull(this.motion)) {
                return '';
            }
            return this.motion.content;
        },
        set(v) {

            //If they cleared the draft and the window is st

            let p = Payload.factory({
                    'object': this.motion,
                    'updateProp': 'content',
                    'updateVal': v
                }
            );

            if(isReadyToRock(this.editMode) && this.editMode===true){
                //dev If we decide to re-enable editing by chair
            }else{
                this.$store.dispatch('updateDraftMotion', p);
            }

            //
            // if (_.isUndefined(this.motion) || _.isNull(this.motion)) {
            //     //initialize first if no motion exists
            //     let me = this;
            //     this.$store.dispatch('createMotion', this.meeting.id).then(function () {
            //         me.$store.dispatch('updateMotion', p);
            //
            //         me.$store.dispatch('updateMotion', p);
            //     });

            // } else {
                //otherwise we can just update as normal
                // this.$emit('update:content', p.updateVal);
                // this.$store.dispatch('updateMotion', p);

            // }
        }
    },
}

}
</script>

<style scoped>

</style>
