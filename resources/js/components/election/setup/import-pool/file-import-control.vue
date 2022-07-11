<template>
    <div class="file-import-control  "
         v-bind:class="styling">

        <label v-bind:for="inputId"
               class="form-label"
        >Import from .csv file</label>

        <input class="form-control form-control-lg"
               v-bind:id="inputId"
               type="file"
               v-on:change.prevent="processFile"
        >

        <!--       This was working version before VOT-123-->
<!--        <label class="file-label">-->
<!--            &lt;!&ndash;This is the native element which is hidden by css&ndash;&gt;-->
<!--            <input id="file-input"-->
<!--                   class="file-input"-->
<!--                   type="file"-->
<!--                   name="candidate-file-upload"-->
<!--                   v-on:change.prevent="processFile"-->
<!--            >-->
<!--        </label>-->



    </div>

</template>

<style lang="scss">
//@import ~bootstrap/scss/variables

//$form-file-button-color : blue

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


        props: ['actionName'],

        components: {},

        data: function () {
            return {
                buttonLabel: 'Select file',



                styling: '', //'btn btn-primary',
                events: {
                    importComplete: 'import-complete',
                    importError: ''
                },

                defaults: {}
            }
        },

        computed: {
            inputId : function(){
                return 'fileInput' + this.actionName;
            }
        },

        methods: {

            processFile: function () {
                let me = this;
                let p = new Promise( function ( resolve, reject ) {

                    let f = document.getElementById( me.inputId );
                    let file = f.files[ 0 ];

                    //Need motion id for when used in importing pool; need meeting id for
                    //when used in importing offices
                    // let payload;
                    // switch (me.actionName){
                    //   case 'createOfficesFromFile':
                    //       payload = {file: file, motionId: me.motion.id, meetingId : me.meeting.id }
                    // }
//                    me.$store.dispatch( me.actionName, {file: file, motionId: me.motion.id, meetingId : me.meeting.id });

                    me.$store.dispatch( me.actionName, file);

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
