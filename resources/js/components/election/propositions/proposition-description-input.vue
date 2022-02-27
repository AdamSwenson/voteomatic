<template>
    <div class="prop-description-input">
<!--    <div class="form-group">-->
<!--        <label for="motion-descrip">Instructions et cetera</label>-->
<!--        <textarea id="motion-description"-->
<!--                  class="form-control"-->
<!--                  rows="5"-->
<!--                  v-model="description"-->
<!--                  v-bind:placeholder="placeholders.description"-->
<!--        ></textarea>-->
<h3>Instructions, et cetera</h3>
        <wysiwyg v-model="description"
                 id="motion-descrip"

        />


    </div>


</template>

<script>
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionMixin from "../../../mixins/motionStoreMixin";
import Payload from "../../../models/Payload";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
name: "proposition-description-input",
    mixins: [MeetingMixin], //, MotionMixin],


    props : ['motion', 'editMode'],


data : function(){
    return {
        placeholders: {
            description: "Place instructions or other information here"
        }
    }
},

computed : {
    description: {
        get: function () {
            if (_.isUndefined(this.motion) || _.isNull(this.motion)) {
                return '';
            }
            return this.motion.description;
        },
        set(v) {

            //If they cleared the draft and the window is st

            let p = Payload.factory({
                    'object': this.motion,
                    'updateProp': 'description',
                    'updateVal': v
                }
            );

            if(isReadyToRock(this.editMode) && this.editMode===true){
                this.$emit('update:description', p.updateVal);
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
                // this.$emit('update:description', p.updateVal);
                // this.$store.dispatch('updateMotion', p);

            // }
        }
    },
}

}
</script>

<style scoped>

</style>
