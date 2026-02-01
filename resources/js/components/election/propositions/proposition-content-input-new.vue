<template>
    <div class="proposition-content-input">
        <label for="motionContent" class='form-label'
        > What is to be voted upon</label>

        <div class="border border-dark">

            <!--           Model does not seem to work even though the docs make it look like it should v-model="content"-->
            <medium-editor
                :options="options"

                :onChange="onChange"
                :prefill="localText"

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


import MediumEditor from 'vuejs-medium-editor'
import 'medium-editor/dist/css/medium-editor.css'
import 'vuejs-medium-editor/dist/themes/default.css'

// import VueTrix from "vue-trix";
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
            _localText: '',

            currentMotion: null,

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
        /**
         * This holds the content for the box locally.
         * This is necessary because otherwise the cursor would jump
         * to be beginning of the text with every update because it
         * thought that we were getting a new motion object.
         *
         * The check of motion id is then necessary so that it will actually
         * recognize changes of motion.
         *
         * Clearly this is not the correct way to do this, but it works for now
         */
        localText: {
            get: function () {
                if (_.isUndefined(this.motion.content)) return ''

                //Checks to see if the motion id has changed and
                //if so updates the text. Otherwise will not update when the curent
                //motion changes
                if (this._localText === '' || this.currentMotion !== this.motion.id) {
                    this._localText = this.motion.content;
                    this.currentMotion = this.motion.id;
                }

                return this._localText;
            },

            set: function (v) {
                this._localText = v;
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
                this.$emit('update:content', p);
            } else {
                this.$store.dispatch('updateDraftMotion', p);
            }

        },


    }

}
</script>

<style scoped>

</style>
