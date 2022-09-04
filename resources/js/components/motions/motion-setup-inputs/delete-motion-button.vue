<!--<template>-->
<!--    <button-->
<!--        v-bind:class="styling"-->
<!--        data-bs-toggle="modal"-->
<!--        data-bs-target="#deleteMotionModal"-->
<!--    >{{label}}</button>-->


<!--</template>-->

<script>

import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import ModalButtonParent from "../../parents/modal-button-parent";
export default {
    name: "delete-motion-button",

    props: [],

    extends : ModalButtonParent,

    mixins: [MotionStoreMixin],

    data: function () {
        return {
            styling: "btn btn-danger ",
            modalId : 'deleteMotionModal',
            ariaDisabled : false

        }
    },
    asyncComputed: {
        label : function(){
            if(isReadyToRock(this.motion) && this.motion.type === 'proposition') return  "Delete proposition";
            return  "Delete motion";
        },


    },

    computed: {},
    methods: {
    //     handleClick: function () {
    //
    //         let me = this;
    //
    //         //First we create and store a new motion from the
    //         //provided template
    //         let p = this.$store.dispatch('deleteMotion', me.motion)
    //             .then(function () {
    //             });
    //     }
    }
}
</script>

<style scoped>

</style>
