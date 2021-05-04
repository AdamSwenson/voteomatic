<template>
    <button
        v-bind:class="styling"
        data-toggle="modal"
        v-bind:data-target="target"
        v-on:click="handleClick"
    >{{label}}</button>


</template>

<script>

import MeetingMixin from '../../mixins/meetingMixin'
import MotionStoreMixin from "../../mixins/motionStoreMixin";
import ModeMixin from "../../mixins/modeMixin";

export default {
    name: "delete-button-parent",

    props: [],
    mixins: [MeetingMixin, ModeMixin],

    data: function () {
        return {
            styling: "btn btn-danger "
        }
    },

    computed: {

        modalId : function(){
          return "deleteModal"
        },

        target : function(){
            return '#' + this.modalId;
        }

        // label : function(){
        // },

        // typeCapitalized : function(){
        //     return this.type.toUpperCase();
        // },
        //

    },
    methods: {

        /**
         * While the button just toggles the modal we may
         * also want to add other things that are triggered when
         * the modal is shown
         */
        handleClick: function () {
         }
    }
}
</script>

<style scoped>

</style>
