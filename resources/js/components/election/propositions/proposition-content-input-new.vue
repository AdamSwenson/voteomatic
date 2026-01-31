<template>
    <div class="proposition-content-input">
        <label for="motionContent" class='form-label'
        > What is to be voted upon</label>

        <div class="border border-dark">

            <!--           Model does not seem to work even though the docs make it look like it should v-model="content"-->
            <medium-editor
                :options="options"

                :onChange="onChange"
                :prefill="content"

                hideImage="true"
                hideVideo="true"
                hideGist="true"

            />
        </div>

    </div>


</template>

<script>
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionMixin from "../../../mixins/motionStoreMixin";
import Payload from "../../../models/Payload";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

// import VueTrix from "vue-trix";

import MediumEditor from 'vuejs-medium-editor'
import 'medium-editor/dist/css/medium-editor.css'
import 'vuejs-medium-editor/dist/themes/default.css'

// import {QuillEditor} from '@vueup/vue-quill'
// import '@vueup/vue-quill/dist/vue-quill.snow.css';
// import '@vueup/vue-quill/dist/vue-quill.bubble.css';

export default {
    name: "proposition-content-input-new",
    mixins: [MeetingMixin], //, MotionMixin],

    components: {
        MediumEditor
    },


    props: ['motion', 'editMode'],


    data: function () {
        return {
            cnt: 'taco',

            options: {
                placeholder: {
                    /* This example includes the default options for placeholder,
                              if nothing is passed this is what it used */
                    text: '  Enter the text of the proposition to be voted upon',
                    hideOnClick: true
                },
                toolbar: {
                    buttons: [
                        'bold',
                        'italic',
                        'underline',
                        'quote',
                        'h1',
                        'h2',
                        'h3',
                        'pre',
                        'unorderedlist',
                    ]
                }
            },
            placeholders: {
                content: "that tacos be declared the official food of this body.",
                description: "(This is currently unused)"
            }
        }
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
                window.console.log('proposition-content-input-new', 'set', 68, v);
                //If they cleared the draft and the window is st

                let p = Payload.factory({
                        'object': this.motion,
                        'updateProp': 'content',
                        'updateVal': v
                    }
                );

                if (isReadyToRock(this.editMode) && this.editMode === true) {
                    this.$emit('update:content', p.updateVal);
                } else {
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
            watch: ['motion']
        }
    },
    methods: {
        onChange: function (value) {
            window.console.log('proposition-content-input-new', 'onChange', 135, value);


            let p = Payload.factory({
                    'object': this.motion,
                    'updateProp': 'content',
                    'updateVal': value
                }
            );

            if (isReadyToRock(this.editMode) && this.editMode === true) {
                window.console.log('proposition-content-input-new', 'onChange', 162, 'edit');
                this.$emit('update:content', p.updateVal);
            } else {
                this.$store.dispatch('updateDraftMotion', p);
            }

        },

        uploadCallBack: function (v) {
        },

    }

}
</script>

<style scoped>

</style>
