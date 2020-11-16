<template>

    <button

        v-bind:class="styling"
        v-on:click="handleClick">
        {{ name }}
    </button>


</template>

<script>
import Payload from "../../../models/Payload";
import MeetingMixin from "../../storeMixins/meetingMixin";

export default {
    name: "motion-template-button",

    props: ["template"],

    mixins: [MeetingMixin],

    data: function () {
        return {
            styling: "btn btn-outline-info motion-template-button"
        }
    },

    computed: {

        name: function () {
            return this.template.name;
        }

    },
    methods: {
        handleClick: function () {
            let me = this;

            //First we create and store a new motion from the
            //provided template
            let p = this.$store.dispatch('createMotion', me.meeting.id)
                .then(function () {

                    _.forEach(me.template, (v, k) => {
                        // window.console.log('new notion', k, v);

                        let pl = Payload.factory({
                            updateProp: k,
                            updateVal: v
                        });

                        me.$store.dispatch('updateMotion', pl)
                            .then(function () {
                                //Finally we emit an event so the parent can
                                //change what fields are displayed if needed
                                me.$emit('motion-created');
                            });


                    });

                });

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
