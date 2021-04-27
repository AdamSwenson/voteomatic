<template>

    <!-- Modal -->
    <div class="modal fade"
         id="deleteMeetingModal"
         tabindex="-1"
         aria-labelledby="deleteMeetingModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMeetingModalLabel">Delete {{ typeCapitalized }}</h5>
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p> You are about to permanently delete this {{ type }}.</p>
                    <p>All of the {{ type }}'s {{ subsidiaryType }} and votes will also be deleted.</p>
                    <p>This cannot be undone</p>
                    <p>Are you sure?</p>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                    >No
                    </button>

                    <button type="button"
                            class="btn btn-primary"
                            data-dismiss="modal"
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

/**
 * Note, this will require that the delete-meeting-button is
 * included elsewhere on the page. They are linked via  bootstrap
 * using the data-dismiss=modal attribute. They are not linked
 * by vue or vuex events.
 */
export default {
    name: "delete-meeting-modal",

    props: [],

    mixins: [MeetingMixin],

    data: function () {
        return {}
    },

    computed: {
        // type: function () {
        //     return this.meeting.type;
        // },
        //
        // typeCapitalized: function () {
        //     return this.type.toUpperCase();
        // },


    },

    methods: {
        handleClick: function () {

            let me = this;

            //First we create and store a new meeting from the
            //provided template
            let p = this.$store.dispatch('deleteMeeting', me.meeting)
                .then(function () {
                });
        }

    }


}

</script>

<style scoped>

</style>
