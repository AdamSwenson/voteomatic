<template>

    <div class="vote-page">
        <div class="row">
            <div class="col">
                <motion-content :motion="motion"></motion-content>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <motion-description :motion=motion></motion-description>

            </div>
        </div>

        <div class="controls row">
            <div class="col"></div>
            <div class="col">

                <vote-receipt :receipt="receipt" v-if="vote"></vote-receipt>


                <vote-buttons v-else
                    :motion="motion"
                    v-on:yay-clicked="handleYay"
                    v-on:nay-clicked="handleNay"
                ></vote-buttons>

            </div>
            <div class="col"></div>

        </div>


        <div class="instructions row">
            <div class="col">
                <p>{{ instructions }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <required-vote :motion="motion"></required-vote>

            </div>
        </div>

    </div>
</template>

<script>

import Vote from '../models/Vote';
import VoteButtons from "./vote-buttons";
import RequiredVote from "./text-display/required-vote";
import MotionContent from "./text-display/motion-content";
import MotionDescription from "./text-display/motion-description";
import * as routes from "../routes";
import VoteReceipt from "./text-display/vote-receipt";

export default {
    name: "vote-page",
    components: {VoteReceipt, MotionDescription, MotionContent, RequiredVote, VoteButtons},
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
            let voteType = 'nay';
            this.recordVote(voteType);
        },

        /**
         * Fires when receives notification that the
         * yay button has been pressed. Sends result
         * to server.
         */
        handleYay: function () {
            let voteType = 'yay';
            this.recordVote(voteType);
        },

        /**
         * Sends vote to server
         *
         * @param voteType
         */
        recordVote: function (voteType) {
            let url = routes.votes.recordVote(this.motion.id);
            let data = {
                motionId: this.motion.id,
                vote: voteType
            };

            return new Promise((resolve, reject) => {

                Vue.axios.post(url, data).then((response) => {

                    if(response.error){
                        //todo error handling
                        this.voteRecorded = true;

                    }else {
                        console.log(response.data);

                        this.vote = new Vote(response.data.isYay, response.data.receipt);
                        this.voteRecorded = true;
                        //todo once receives notification that vote has been recorded, should set voteRecorded to true so inputs can be disabled.
                        resolve();
                    }
                });


            });

        }
    },
    computed: {
        receipt : function(){
            if(this.vote){
                return this.vote.receipt;
            }
        },

        // content: function () {
        //     return this.motion.content;
        // },
        //
        // description: function () {
        //     return this.motion.description;
        // },

        instructions: function () {
            return "Some generic instructions...."
        }
    }
}
</script>

<style scoped>

</style>
