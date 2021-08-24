<template>
    <div class="button-area">
        <div class="row">
            <div class="col">

                <nay-button ></nay-button>

                <vote-confirmation-modal type="nay"
                                         v-on:nay-clicked="handleNay"
                ></vote-confirmation-modal>
            </div>

            <div class="col"></div>

            <div class="col">
                <yay-button ></yay-button>

                <vote-confirmation-modal type="yay"
                                         v-on:yay-clicked="handleYay"
                ></vote-confirmation-modal>

            </div>
        </div>
    </div>
</template>

<script>
import YayButton from "./yay-button";
import NayButton from "./nay-button";
import * as routes from "../../routes";
import VoteConfirmationModal from "./vote-confirmation-modal";

export default {
    name: "vote-buttons",
    components: {VoteConfirmationModal, NayButton, YayButton},
    props: ['motion'],

    data: function () {
        return {
            voteRecorded: false,
            vote: null
        }
    },

    methods: {
        /**
         * Fires when receives notification that the
         * nay button has been pressed. .
         */
        handleNay: function () {
            this.$emit('nay-clicked');
        },

        /**
         * Fires when receives notification that the
         * yay button has been pressed.
         */
        handleYay: function () {
            this.$emit('yay-clicked');

        },
    }
}
</script>

<style scoped>

</style>
