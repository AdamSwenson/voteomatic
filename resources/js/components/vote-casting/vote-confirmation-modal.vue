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
                            class="btn btn-secondary"
                            data-dismiss="modal"
                    >No
                    </button>

                    <button type="button"
                            class="btn btn-primary"
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
            let voteEvent = this.type + '-clicked';

            this.$emit(voteEvent);
        }

    }


}

</script>

<style scoped>

</style>

