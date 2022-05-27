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
                    >Delete {{ typeCapitalized }}</h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
<!--                    <button type="button"-->
<!--                            class="btn-close"-->
<!--                            data-bs-dismiss="modal"-->
<!--                            aria-label="Close">-->
<!--                        <span aria-hidden="true">&times;</span>-->
<!--                    </button>-->
                </div>

                <div class="modal-body" v-html="modalText">
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-ds-dismiss="modal"
                    >No
                    </button>

                    <button type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                            v-on:click="handleClick"
                    >Yes. Delete it.
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
 * Note, this will require that the delete-meeting-button is
 * included elsewhere on the page. They are linked via  bootstrap
 * using the data-bs-dismiss=modal attribute. They are not linked
 * by vue or vuex events.
 */
export default {
    name: "delete-modal-parent",

    props: [],

    mixins: [MeetingMixin, ModeMixin],

    data: function () {
        return {}
    },

    computed: {
        modalId: function () {
            return "deleteModal"
        },

        labelId: function () {
            return "delete" + _.capitalize(this.eventType) + "ModalLabel";
        },

        modalText: function () {

            return `<p> You are about to permanently delete this ${this.eventType}.</p>
            <p>All of the ${this.eventType}'s ${this.subsidiaryType} and votes will also be deleted.</p>
            <p>This cannot be undone</p>
            <p>Are you sure?</p>`;
        },


        /**
         * First letter capitalized for use in labels etc
         * @returns {string}
         */
        typeCapitalized : function(){
            return _.capitalize(this.eventType);
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
