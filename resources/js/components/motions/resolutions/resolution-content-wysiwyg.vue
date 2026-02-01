<template>

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


</template>

<script>
import MediumEditor from 'vuejs-medium-editor'
import 'medium-editor/dist/css/medium-editor.css'
import 'vuejs-medium-editor/dist/themes/default.css'
import MeetingMixin from "../../../mixins/meetingMixin";
import Payload from "../../../models/Payload";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "resolution-content-wysiwyg",
    // mixins: [MeetingMixin], //, MotionMixin],

    components: {
        MediumEditor
    },

    // props: ['motion'],
props : ['text'],

    data: function () {
        return {

            _localText: '',

            options: {
                placeholder: {
                    /* This example includes the default options for placeholder,
                              if nothing is passed this is what it used */
                    text: '  Enter the text of the resolution',
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

        localText: {
            get: function () {
                if (this._localText === '') {
                    if (_.isUndefined(this.text)) return ''
                    this._localText = this.text;
                }
                return this._localText;

            },

            set: function (v) {
                this._localText = v;
            }
        },

    },
    methods: {

        onChange: function (value) {
            // window.console.log('proposition-content-input-new', 'onChange', 135, value);
            this.$emit('updatetext', value);
        }
    }

}
</script>


<style scoped>

</style>
