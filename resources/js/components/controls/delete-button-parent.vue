<template>
    <button
        v-bind:class="styling"
        data-bs-toggle="modal"
        v-bind:data-bs-target="target"
        v-on:click="handleClick"
    > <i  v-if="hasIcon" class="bi " v-bind:class="icon"></i> {{label}}</button>


</template>

<script>

import MeetingMixin from '../../mixins/meetingMixin'
import MotionStoreMixin from "../../mixins/motionStoreMixin";
import ModeMixin from "../../mixins/modeMixin";
import {isReadyToRock} from "../../utilities/readiness.utilities";

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
        },

        hasIcon: function(){
            return isReadyToRock(this.icon);
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
