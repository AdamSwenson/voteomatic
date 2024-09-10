<template>


    <div class="form-group description-input">

        <label  class='form-label' for="description">Optional description or instructions</label>

        <textarea id="description"
                  class="form-control"
                  v-model="description"
                  v-bind:placeholder="placeholders.description"
        ></textarea>

    </div>

</template>


<script>
import Payload from "../../../models/Payload";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionMixin from "../../../mixins/motionStoreMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "description-input",

    props : ['motion', 'editMode'],

    mixins: [MeetingMixin], //MotionMixin],

    data: function () {
        return {

            placeholders: {
                description: "A brief description or explanation pertaining to the motion which will be" +
                    "displayed with the motion on the voting page"
            }
        }
    },

    computed: {
        description: {
            get: function () {
                try {
                    return this.motion.description;
                } catch (e) {
                    return ''
                }
            },
            set(v) {
                let p = Payload.factory({
                        'object': this.motion,
                        'updateProp': 'description',
                        'updateVal': v
                    }
                );

                if(isReadyToRock(this.editMode) && this.editMode===true){
                    this.$emit('update:content', p.updateVal);
                }else{
                    this.$store.dispatch('updateDraftMotion', p);
                }

            }
        },
    }

}
</script>

<style scoped>

</style>
