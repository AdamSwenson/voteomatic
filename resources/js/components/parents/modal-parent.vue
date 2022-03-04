<template>

    <!-- Modal -->
    <div class="modal fade"
         v-bind:id="modalId"
         tabindex="-1"
         v-bind:aria-labelledby="labelId"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title"
                        v-bind:id="labelId"
                    >{{ modalTitle }}</h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>

                </div>

                <div class="modal-body" >
                    <div class="modalText" v-html="modalText"></div>
                    <slot>
                        <div class="modalText" v-html="modalSecondaryText"></div>
                    </slot>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                    >Cancel
                    </button>


                    <button type="button"
                            class="btn btn-primary"
                            v-if="showActionButton"
                            data-bs-dismiss="modal"
                            v-on:click="handleClick"
                    >{{buttonLabel}}</button>

                </div>
            </div>
        </div>
    </div>

</template>

<script>

import MeetingMixin from '../../mixins/meetingMixin'
import ModeMixin from "../../mixins/modeMixin";
import {isReadyToRock} from "../../utilities/readiness.utilities";

/**
 * Note, this will require that a corresponding button which inherits from
 * modal-button-parent is included elsewhere on the page
 * They are linked via  bootstrap
 * using the data-bs-dismiss=modal attribute.
 *
 * Content of the modal is either defined via the slot
 *      <modal-child>
 *          stuff that goes in modal
 *       </modal-child>
 * or by defining modalSecondaryText in data or property
 *
 * Children must define (data or property):
 *      modalId
 *      modalTitle
 *      handleClick
 *      buttonLabel : The label on the action button
 *
 * Children may define (data or property):
 *      styling
 *      modalText
 *      hideActionButton : Boolean of whether to hide the action button.
 *                          mostly used when a button will be used in the slot
 *
 */
export default {
    name: "modal-parent",

    props: [],

    mixins: [MeetingMixin, ModeMixin],

    // data: function () {
    //     return {}
    // },

    computed: {
        showActionButton : function(){
            if(! isReadyToRock(this.hideActionButton)) return true;
            return ! this.hideActionButton;
        },
        // buttonLabel : function (){},
        // modalId: function () {
        //     return ""
        // },

        labelId: function () {
            return "modalLabelId" + this.modalId;
        },

        // modalTitle: function(){},

        // modalText: function () {
        //     return `<p></p>`;
        //  },

        //
        // /**
        //  * First letter capitalized for use in labels etc
        //  * @returns {string}
        //  */
        // typeCapitalized : function(){
        //     // return _.capitalize(this.eventType);
        // },



    },

    methods: {
        closeModal : function(){
            $('#' + this.modalId).modal('hide');
        },

    }
    //     handleClick: function () {
    //         //
    //         //     let me = this;
    //         //
    //         //     //First we create and store a new meeting from the
    //         //     //provided template
    //         //     let p = this.$store.dispatch('deleteMeeting', me.meeting)
    //         //         .then(function () {
    //         //         });
    //         // }
    //
    //     }
    //
    // }
}

</script>

<style scoped>

</style>
