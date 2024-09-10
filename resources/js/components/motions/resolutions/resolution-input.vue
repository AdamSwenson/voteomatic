<template>
    <div class="resolution-input">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="btn-group text-end" role="group" aria-label="Editor type toggle">
                    <input type="radio"
                           class="btn-check"
                           id="editorToggle1"
                           autocomplete="off"
                           v-model="useWYSIWYG"
                           value=""
                           v-bind:checked="! useWYSIWYG">
                    <label class="btn btn-outline-primary"
                           for="editorToggle1"
                    >Plain text entry</label>

                    <input type="radio"
                           class="btn-check"
                           id="editorToggle2"
                           autocomplete="off"
                           v-model="useWYSIWYG"
                           value="true"
                           v-bind:checked="useWYSIWYG">
                    <label class="btn btn-outline-primary" for="editorToggle2">WYSIWYG</label>
                </div>
                <div class="form-text">Use plain text entry to paste text containing HTML tags</div>
            </div>
        </div>

        <div class="wysiwyg-entry" v-if="useWYSIWYG">
            <wysiwyg v-model="content"/>
        </div>

        <div class="plain-text-entry" v-else>
            <label for="rezzieInput" class="form-label">Resolution text</label>

            <textarea id="rezzieInput" class="form-control" v-model="content" rows="30"
                      aria-describedby="rezzieInputInfo"></textarea>

            <div class="rezzieInputInfo form-text">
                Add HTML formatted resolution text here. NB, this may create an XSS vulnerability. Be careful!
            </div>
        </div>

    </div>
</template>

<script>
import Payload from "../../../models/Payload";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MeetingMixin from "../../../mixins/meetingMixin";

export default {
    name: "resolution-input",
    mixins: [MeetingMixin], //, MotionMixin],


    props: ['motion', 'editMode'],

    data: function () {
        return {
            useWYSIWYG: ''
        }
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

    methods: {

    }

}
</script>

<style scoped>

</style>
