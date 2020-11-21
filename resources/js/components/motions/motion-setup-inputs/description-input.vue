<template>


    <div class="form-group description-input">

        <label for="description">Optional description or instructions</label>

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
import MotionMixin from "../../../mixins/motionMixin";

export default {
    name: "description-input",

    props: [],


    mixins: [MeetingMixin], //MotionMixin],

    data: function () {
        return {

            placeholders: {
                content: "that tacos be declared the official food of this body.",
                description: "(This is currently unused)"
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

//                this.$store.dispatch('updateMotion', p);
                this.$emit('update:description', p.updateVal);

            }
        },
    }

}
</script>

<style scoped>

</style>
