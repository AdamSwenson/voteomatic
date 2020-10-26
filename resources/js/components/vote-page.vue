<template>
    <div class="card vote-page">

        <div class="card-content">

            <div class="vote-area card-body"
                 v-if="isReady"
            >

                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Please vote on this motion</h5>
                    </div>

                    <div class="card-text">
                        <p class="motion-content "
                        >{{ motionContent }}</p>
                        <!--                    <motion-content :motion="motion"></motion-content>-->
                    </div>


                    <vote-receipt :receipt="receipt" v-if="vote"></vote-receipt>

                    <div class="card-footer">
                        <div class="text-right">
                            <vote-buttons
                                :motion="motion"
                                v-on:yay-clicked="handleYay"
                                v-on:nay-clicked="handleNay"
                            ></vote-buttons>
                        </div>
                    </div>

                </div>
                <!--        <div class="col"></div>-->
                <!--        </div>-->

                <required-vote :motion="motion"></required-vote>

                <!--                <h5 class="card-subtitle">Specific instructions / notes</h5>-->
                <!--                <div class="row">-->
                <!--                    <div class="col">-->
                <!--                        &lt;!&ndash;                <motion-description :motion=motion></motion-description>&ndash;&gt;-->
                <!--                    </div>-->
                <!--                </div>-->

                <!--                <h5>General instructions</h5>-->
                <!--                <div class="instructions row">-->
                <!--                    <div class="col">-->
                <!--                        <p>{{ instructions }}</p>-->
                <!--                    </div>-->
                <!--                </div>-->

            </div>

            <div class="card-body loading-message" v-else>
                <p>Please wait while your ballot loads.....</p>
            </div>

        </div>
    </div>
</template>

<script>

import Vote from '../models/Vote';
import VoteButtons from "./vote-buttons";
import RequiredVote from "./text-display/required-vote";
// import MotionContent from "./text-display/motion-content";
// import MotionDescription from "./text-display/motion-description";
import * as routes from "../routes";
import VoteReceipt from "./text-display/vote-receipt";
import motionMixin from '../components/storeMixins/motionMixin';

export default {
    name: "vote-page",
    components: {
        VoteReceipt,
        // MotionDescription, MotionContent,
        RequiredVote, VoteButtons
    },
    props: [],

    mixins: [motionMixin],

    data: function () {
        return {
            voteRecorded: false,
            vote: null,

            // isReady: true
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

                return Vue.axios.post(url, data).then((response) => {

                    if (response.error) {
                        //todo error handling
                        this.voteRecorded = true;

                    } else {
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

    asyncComputed: {
        isReady: {
            get: function () {

                return !_.isUndefined(this.motion) && !_.isNull(this.motion);
            },
            watch: ['motion']
        },

        motionContent: function () {
            if (this.isReady) {
                // get: function () {
                return this.motion.content;
            }
            // },
            // default: ''
        },

        motionDescription: function () {
            if (this.isReady) {
                // get: function () {
                return this.motion.content;
            }
            // },
            // default: ''
        },
        //
        //     {
        //     get: function () {
        //         return this.motion.description;
        //     },
        //     default: ''
        // }
    },

    computed: {
        receipt: function () {
            if (this.vote) {
                return this.vote.receipt;
            }
        }
        ,

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
.motion-content {
    padding: 6em
}
</style>
