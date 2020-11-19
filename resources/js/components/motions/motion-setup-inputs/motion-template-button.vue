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
import MotionMixin from "../../storeMixins/motionMixin";
import RoutingMixin from "../../routingMixin";

export default {
    name: "motion-template-button",

    props: ["template"],

    mixins: [MeetingMixin, MotionMixin, RoutingMixin],

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

        makeMain : function(){
            //First we create and store a new motion from the
            //provided template
            let p = this.$store.dispatch('createMotion', me.meeting.id)
                .then(function () {
                        // return new Promise(((resolve, reject) => {

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


                    me.openHomeTab();
                        // });
                });
        },

        makeSubsidiary : function(){
            let payload = {
                meetingId: this.meeting.id,
                applies_to: this.motion.id,
                content: this.localText,
                type: this.template.type,
                requires: this.template.requires
            };

            let p = this.$store.dispatch('createSubsidiaryMotion', payload);
            let me = this;
            p.then(() => {
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

                me.openHomeTab();
            });
        },

        handleClick: function () {
            let me = this;
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
