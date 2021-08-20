<template>
    <div class="form-group">
        <label for="typeSelect">Motion type</label>
        <select
            id="typeSelect"
            class="form-control disabled"
            v-model="motionType">
            <option disabled value="">Please select motion type</option>
            <option>Main motion</option>
            <option>Amendment</option>
            <option>Procedural motion</option>
        </select>
    </div>


</template>

<script>

import Payload from "../../../models/Payload";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionMixin from "../../../mixins/motionStoreMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "motion-type-input",

    props: ['motion', 'editMode'],

    mixins: [MeetingMixin],// MotionMixin],

    data: function () {
        return {}
    },

    computed: {

        motionType: {
            get: function () {
                try {
                    return this.motion.type;
                } catch (e) {
                    return ''
                }
            },
            set: function (v) {
                let p = Payload.factory({
                        'object': this.motion,
                        'updateProp': 'type',
                        'updateVal': v
                    }
                );

                if(isReadyToRock(this.editMode) && this.editMode===true){
                    this.$emit('update:content', p.updateVal);
                }else{
                    this.$store.dispatch('updateDraftMotion', p);
                }

                // this.$store.dispatch('updateMotion', p);
//                this.$emit('update:type', p.updateVal);

            }
        },

    }

}
</script>

<style scoped>

</style>
