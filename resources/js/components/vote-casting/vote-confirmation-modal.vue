<template>

    <!-- Modal -->
    <div class="modal fade"
         v-bind:id="modalId"
         tabindex="-1"
         aria-labelledby="voteModalLabel"
         aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="voteModalLabel">Confirm your vote</h5>
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p class="text-dark">{{ modalText }}</p>
                    <p>Once you cast your vote, it cannot be changed.</p>
                    <p>Are you sure?</p>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary no"
                            data-dismiss="modal"
                    >No
                    </button>

                    <button type="button"
                            class="btn btn-primary yes"
                            data-dismiss="modal"
                            v-on:click="handleClick"
                    >Yes. Record my vote.
                    </button>

                </div>
            </div>
        </div>
    </div>

</template>

<script>

import MotionMixin from '../../mixins/motionStoreMixin'
import Vote from "../../models/Vote";

/**
 * Note, this will require that the delete-motion-button is
 * included elsewhere on the page. They are linked via  bootstrap
 * using the data-dismiss=modal attribute. They are not linked
 * by vue or vuex events.
 */
export default {
    name: "vote-confirmation-modal",

    props: ['type'],

    mixins: [MotionMixin],

    data: function () {
        return {}
    },

    computed: {

        /**
         * The id of the modal
         * Will be either yayConfirmationModal or nayConfirmationModal
         * @returns {string}
         */
        modalId: function () {
            return this.type + 'ConfirmationModal';
        },

        modalText: function () {
            switch (this.type) {
                case 'yay' :
                    return "You are voting in favor of the motion.";
                    break;
                case 'nay':
                    return "You are voting against the motion";
                    break;

            }

        }

    },

    methods: {
        handleClick: function () {
            if (this.type === 'nay') {
                this.handleNay();
            } else if (this.type === 'yay') {
                this.handleYay();
            }


            let voteEvent = this.type + '-clicked';
            this.$emit(voteEvent);
        },


        /**
         * Fires when receives notification that the
         * nay button has been pressed. Sends result
         * to server.
         */
        handleNay: function () {

            let vote = new Vote(
                {
                    motionId: this.motion.id,
                    isYay: false
                });
            this.recordVote(vote);
        },

        /**
         * Fires when receives notification that the
         * yay button has been pressed. Sends result
         * to server.
         */
        handleYay: function () {
            let vote = new Vote(
                {
                    motionId: this.motion.id,
                    isYay: true
                });

            this.recordVote(vote);
        },

        /**
         * Handles sending the result to the server
         * @param vote Vote object
         */
        recordVote: function (vote) {

            this.$store.dispatch('castMotionVote', vote).then((v) => {
                if (v.receipt.length > 0) {
                    //Successfully recorded

                }
            }).catch((error) => {
                if (error.response) {
                    // The request was made and the server responded with a status code
                    // that falls out of the range of 2xx
                    console.log(error.response.data);
                    console.log(error.response.status);
                    if (error.response.status === 501) {

                    }
                }

            });


        }
    }


}

</script>

<style scoped>

</style>

