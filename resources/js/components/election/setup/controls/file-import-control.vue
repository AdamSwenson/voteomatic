<template>
    <div class="file-import-control  "
         v-bind:class="styling"
    >
<!--<label for="file-input">{{buttonLabel}}</label>-->
<!--                    <input id="file-input"-->
<!--                           class="file-input"-->
<!--                           type="file"-->
<!--                           name="candidate-file-upload"-->
<!--                           v-on:change.prevent="processFile"-->
<!--                    >-->

<!--                    &lt;!&ndash;This is the call to action, i.e., the text&ndash;&gt;-->
<!--                    <span class="file-cta">-->
<!--                            <span class="file-icon"><i class="fa fa-upload"></i></span>-->
<!--                            <span class="file-label">{{buttonLabel}}</span>-->
<!--                        </span>-->



        <!--        <div class="custom-file">-->
<!--            <input  type="file" class="custom-file-input" id="customFile"-->
<!--                    name="candidate-file-upload"-->
<!--                    v-on:change.prevent="processFile">-->
<!--            <label class="custom-file-label" for="customFile">Choose file</label>-->
<!--        </div>-->

        <label class="file-label">

            <!--This is the native element which is hidden by css-->
            <input id="file-input"
                   class="file-input"
                   type="file"
                   name="candidate-file-upload"
                   v-on:change.prevent="processFile"
            >

            <!--This is the call to action, i.e., the text-->
<!--            <span class="file-cta">-->
<!--                    <span class="file-icon"><i class="fa fa-upload"></i></span>-->
<!--                    <span class="file-label">{{buttonLabel}}</span>-->
<!--                </span>-->

        </label>

    </div>

</template>

<style lang="scss">

//.btn-file {
//    position: relative;
//    overflow: hidden;
//}
//.btn-file input[type=file]
//position: absolute
//top: 0
//right: 0
//min-width: 100%
//              min-height: 100%
//                             font-size: 100px
//                                           text-align: right
//                                           filter: alpha(opacity=0)
//opacity: 0
//outline: none
//background: white
//cursor: inherit
//display: block

</style>

<script>
    import MeetingMixin from "../../../../mixins/meetingMixin";
    import MotionStoreMixin from "../../../../mixins/motionStoreMixin";
    import ModeMixin from "../../../../mixins/modeMixin";
    import ChairMixin from "../../../../mixins/chairMixin";

    export default {
        name: 'file-import-control',

        mixins: [MeetingMixin, MotionStoreMixin, ModeMixin, ChairMixin,],


        props: [],

        components: {},

        data: function () {
            return {
                buttonLabel: 'Select file',

                styling: '', //'btn btn-primary',
                events: {
                    importComplete: 'candidate-import-complete',
                    importError: ''
                },

                defaults: {}
            }
        },

        computed: {},

        methods: {

            processFile: function () {
                let me = this;
                let p = new Promise( function ( resolve, reject ) {

                    let f = document.getElementById( 'file-input' );
                    let file = f.files[ 0 ];

                    //processFile gets called once
                    //as indicated by this line only printing once
                    // window.console.log( 'candidates-panel', 'processFile', 112, evt, f, file );

                    //but then it seems this line gets called twice....
                    //since all the messages for importcandidatesFromFile
                    //display twice
                    me.$store.dispatch( 'createPoolFromFile', {file: file, motionId: me.motion.id });

                    // window.console.log( 'candidates-panel', 'processFile', 332, 'after the dispatch has weirdly fired twice' );
                    //finally, reset the attached file
                    f.value = '';
                    resolve();
                } );

                p.then( function () {
                    me.notifyParentImportComplete();
                } );

                p.catch( function () {
                    //todo
                } );
            },

            notifyParentImportComplete: function () {
                return this.$emit( this.events.importComplete );
            }

        },

    }
</script>
