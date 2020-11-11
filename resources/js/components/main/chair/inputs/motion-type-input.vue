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

import Payload from "../../../../models/Payload";
import MeetingMixin from "../../../storeMixins/meetingMixin";
import MotionMixin from "../../../storeMixins/motionMixin";

export default {
    name: "motion-type-input",

    props: [],

    mixins: [MeetingMixin, MotionMixin],

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
                this.$store.dispatch('updateMotion', p);

            }
        },

    }

}
</script>

<style scoped>

</style>
