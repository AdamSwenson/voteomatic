<template>
    <div class="button-area">
        <div class="row">
            <div class="col text-right">

                <yay-button v-on:yay-clicked="handleYay"></yay-button>

            </div>

            <div class="col text-left">

                <nay-button v-on:nay-clicked="handleNay"></nay-button>

            </div>
        </div>
    </div>
</template>

<script>
import YayButton from "./controls/yay-button";
import NayButton from "./controls/nay-button";
import * as routes from "../routes";

export default {
    name: "vote-buttons",
    components: {NayButton, YayButton},
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
         * nay button has been pressed. Sends result
         * to server.
         */
        handleNay: function () {
            this.$emit('nay-clicked');

            // let voteType = 'nay';
            // this.recordVote(voteType);
        },

        /**
         * Fires when receives notification that the
         * yay button has been pressed. Sends result
         * to server.
         */
        handleYay: function () {
            this.$emit('yay-clicked');

            // let voteType = 'yay';
            // this.recordVote(voteType);
        },
        //
        // /**
        //  * Sends vote to server
        //  *
        //  * @param voteType
        //  */
        // recordVote: function (voteType) {
        //     let url = routes.votes.recordVote(this.motion.id);
        //     let data = {
        //         motionId: this.motion.id,
        //         vote: voteType
        //     };
        //
        //     return new Promise((resolve, reject) => {
        //
        //         Vue.axios.post(url, data).then((response) => {
        //
        //             if(response.error){
        //                 //todo error handling
        //                 this.voteRecorded = true;
        //
        //             }else {
        //
        //                 this.vote = new Vote(response.data.isYay, response.data.receipt);
        //                 this.voteRecorded = true;
        //                 //todo once receives notification that vote has been recorded, should set voteRecorded to true so inputs can be disabled.
        //                 resolve();
        //             }
        //         });
        //
        //
        //     });
        //
        // }
    }
}
</script>

<style scoped>

</style>
