<template>

    <!-- Modal -->
    <div class="modal fade"
         v-bind:id="modalId"
         tabindex="-1"
         :aria-labelledby="modalLabelId"
         aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content" :class="modalClass">
                <div class="modal-header">
                    <h5 class="modal-title" :id="modalLabelId">Confirm your {{ voteLabel }} vote</h5>
                      <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body text-start">
                    <p class="text-dark">{{ modalText }}</p>
                    <p class="mb-0">Once you record this vote, it cannot be changed.</p>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary no"
                            data-bs-dismiss="modal"
                    >No
                    </button>

                    <button type="button"
                            class="btn text-light yes"
                            :class="confirmButtonClass"
                            data-bs-dismiss="modal"
                            v-on:click="handleClick"
                    >Record {{ voteLabel }} vote
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
 * using the data-bs-dismiss=modal attribute. They are not linked
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

        modalLabelId: function () { return this.modalId + 'Label'; },

        voteLabel: function () { return this.type === 'yay' ? 'Aye' : 'Nay'; },

        modalClass: function () { return this.type === 'yay' ? 'vote-confirmation--aye' : 'vote-confirmation--nay'; },

        confirmButtonClass: function () { return this.type === 'yay' ? 'btn-success' : 'btn-danger'; },

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
.vote-confirmation--aye .modal-header { border-top: .35rem solid #198754; }
.vote-confirmation--nay .modal-header { border-top: .35rem solid #dc3545; }
</style>

<style scoped>

</style>
