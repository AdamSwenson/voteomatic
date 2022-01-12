<template>

    <wysiwyg v-model="content" />

</template>

<script>
import Payload from "../../../models/Payload";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MeetingMixin from "../../../mixins/meetingMixin";

export default {
    name: "resolution-input",
    mixins: [MeetingMixin], //, MotionMixin],


    props : ['motion', 'editMode'],

    data: function () {
        return {}
    },

    asyncComputed: {
        //
        // content: {
        //     get: function () {
        //         if (_.isUndefined(this.motion) || _.isNull(this.motion)) {
        //             return '';
        //         }
        //         return this.motion.content;
        //     },
        //     set(v) {
        //         window.console.log('rezzie-input', v);
        //         //If they cleared the draft and the window is st
        //
        //         let p = Payload.factory({
        //                 'object': this.motion,
        //                 'updateProp': 'content',
        //                 'updateVal': v
        //             }
        //         );
        //         //
        //         // if(isReadyToRock(this.editMode) && this.editMode===true){
        //         //     this.$emit('update:content', p.updateVal);
        //         // }else{
        //             this.$store.dispatch('updateDraftMotion', p);
        //         // }
        //
        //     }
        // },

    },

    computed: {

        content: {
            get: function () {
                if (_.isUndefined(this.motion) || _.isNull(this.motion)) {
                    return '';
                }
                return this.motion.content;
            },
            set(v) {
                // window.console.log('rezzie-input', v);
                //If they cleared the draft and the window is st

                let p = Payload.factory({
                        'object': this.motion,
                        'updateProp': 'content',
                        'updateVal': v
                    }
                );
                //
                // if(isReadyToRock(this.editMode) && this.editMode===true){
                //     this.$emit('update:content', p.updateVal);
                // }else{
                this.$store.dispatch('updateDraftMotion', p);
                // }

            }
        },
    },

    methods: {}

}
</script>

<style scoped>

</style>
