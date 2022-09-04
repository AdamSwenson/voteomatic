<template>
    <div class="proposition-content-input">
        <label for="motionContent" class='form-label'
        > What is to be voted upon</label>
        <wysiwyg v-model="content"
                 id="motionContent"
                 name="motion-content"

        />
    </div>

<!--    <div class="form-group">-->
<!--        <label for="motion-content">What is to be voted upon</label>-->
<!--        <textarea id="motion-content"-->
<!--                  class="form-control"-->
<!--                  rows="3"-->
<!--                  v-model="content"-->
<!--                  v-bind:placeholder="placeholders.content"-->
<!--        ></textarea>-->




</template>

<script>
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionMixin from "../../../mixins/motionStoreMixin";
import Payload from "../../../models/Payload";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
name: "proposition-content-input",
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
                this.$emit('update:content', p.updateVal);
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
        },
        watch : ['motion']
    },
}

}
</script>

<style scoped>

</style>
