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
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" v-html="modalText">
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                    >Cancel
                    </button>

                    <button type="button"
                            class="btn btn-primary"
                            data-dismiss="modal"
                            v-on:click="handleClick"
                    >{{buttonLabel}}
                    </button>

                </div>
            </div>
        </div>
    </div>

</template>

<script>

import MeetingMixin from '../../mixins/meetingMixin'
import ModeMixin from "../../mixins/modeMixin";

/**
 * Note, this will require that a corresponding button is
 * included elsewhere on the page, if it isn't launched programatically.
 * They are linked via  bootstrap
 * using the data-dismiss=modal attribute.
 */
export default {
    name: "delete-modal-parent",

    props: [],

    mixins: [MeetingMixin, ModeMixin],

    data: function () {
        return {}
    },

    computed: {
        buttonLabel : function (){},
        modalId: function () {
            return ""
        },

        labelId: function () {
            return "modalLabelId" + this.modalId;
        },

        modalTitle: function(){},

        modalText: function () {
            return `<p></p>`;
         },


        /**
         * First letter capitalized for use in labels etc
         * @returns {string}
         */
        typeCapitalized : function(){
            // return _.capitalize(this.eventType);
        },



    },

    methods: {
        handleClick: function () {
            //
            //     let me = this;
            //
            //     //First we create and store a new meeting from the
            //     //provided template
            //     let p = this.$store.dispatch('deleteMeeting', me.meeting)
            //         .then(function () {
            //         });
            // }

        }

    }
}

</script>

<style scoped>

</style>
