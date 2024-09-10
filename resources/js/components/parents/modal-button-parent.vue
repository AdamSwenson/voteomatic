<template>
    <button
        class="btn "
        v-bind:class="calculatedStyling"
        data-bs-toggle="modal"
        v-bind:data-bs-target="target"
        v-on:click="handleClick"
        v-bind:aria-disabled="ariaDisabled"
    >
        <i  v-if="hasIcon" class="bi " v-bind:class="icon"></i> {{label}}
    </button>


</template>

<script>

import MeetingMixin from '../../mixins/meetingMixin'
import ModeMixin from "../../mixins/modeMixin";
import {isReadyToRock} from "../../utilities/readiness.utilities";

/**
 * Parent button for launching a modal which inherits modal-parent
 * via bootstrap.
 *
 * Children must define (data or property):
 *      modalId
 *      label
 *      ariaDisabled
 *
 * Children may define (data or property):
 *      styling
 *
 */
export default {
    name: "modal-button-parent",

    props: [],

    mixins: [MeetingMixin, ModeMixin],

    computed: {
        calculatedStyling: function(){
            if(isReadyToRock(this.styling)) return this.styling;

            return " btn-primary "
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
