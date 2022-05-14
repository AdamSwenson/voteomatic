<template>
    <div class="card vote-page">

        <div class="card-header">
            <h4 class="card-title">{{ cardTitle }}</h4>
        </div>

        <div class="vote-area card-body">

<!--            <div class="text-center">-->
                <motion-text-display
                    v-if="isReady"
                    :motion="motion"
                ></motion-text-display>
<!--                -->
<!--                <motion-content-->
<!--                    :motion="motion"-->
<!--                    :isReady="isReady"-->
<!--                    v-if="! isAmendment"-->
<!--                ></motion-content>-->

<!--                <amendment-text-display-->
<!--                    v-else-if="isReady && isAmendment"-->
<!--                ></amendment-text-display>-->

<!--            </div>-->

            <vote-receipt
                :receipt="receipt"
                v-if="vote"
            ></vote-receipt>

        </div>

        <div class="card-body">
            <div class="row">
                <div class="col">
                    <required-vote v-if="isReady" :motion="motion"></required-vote>

                    <motion-type-badge v-if="isReady" :motion="motion"></motion-type-badge>

                    <required-vote-badge v-if="isReady" :motion="motion"></required-vote-badge>

                    <debatable-badge v-if="isReady" :motion="motion"></debatable-badge>
                </div>
                <div class="col">

                </div>

                <div class="col">
                    <p class="motionDescription text-muted">{{ motionDescription }}</p>

                </div>
            </div>
        </div>


        <div class="card-footer" v-if="isVotingAllowed">
            <vote-buttons
                v-if="showButtons"
                :motion="motion"
                v-on:yay-clicked="handleYay"
                v-on:nay-clicked="handleNay"
            ></vote-buttons>

            <div
                class="text-center"
                v-else
            >
                <p>You have already voted</p>
            </div>
        </div>

        <div class="card-footer" v-else-if="! isVotingAllowed && ! isComplete">
            <p>The Chair has not yet opened voting for this motion</p>
        </div>

        <div class="card-footer" v-else-if="! isVotingAllowed &&  isComplete">
            <p>Voting has ended on this motion. You may view the results in the results tab</p>
        </div>

        <!--        </div>-->

        <!--        <required-vote :motion="motion"></required-vote>-->


    </div>

    <!--    &lt;!&ndash;            If not ready&ndash;&gt;-->
    <!--    <div class="card-body loading-message"-->
    <!--         v-else>-->
    <!--        <p>Please wait while your ballot loads.....</p>-->
    <!--    </div>-->

    <!--    </div>-->
    <!--    </div>-->
</template>

<script>

import Vote from '../../models/Vote';
import VoteButtons from "../vote-casting/vote-buttons";
import RequiredVote from "../text-display/required-vote";
import MotionContent from "../text-display/motion-content";
// import MotionDescription from "./text-display/motion-description";
import * as routes from "../../routes";
import VoteReceipt from "../text-display/vote-receipt";
import motionMixin from '../../mixins/motionStoreMixin';
import receiptMixin from "../../mixins/receiptMixin";

import motionObjectMixin from "../../mixins/motionObjectMixin";
import AmendmentTextDisplay from "../motions/text-display/amendment-text-display";
import RequiredVoteBadge from "../motions/badges/required-vote-badge";
import DebatableBadge from "../motions/badges/debatable-badge";
import MotionTypeBadge from "../motions/badges/motion-type-badge";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import MotionTextDisplay from "../motions/text-display/motion-text-display";

export default {
    name: "vote-page",
    components: {
        MotionTextDisplay,
        MotionTypeBadge,
        DebatableBadge,
        RequiredVoteBadge,
        AmendmentTextDisplay,
        VoteReceipt,
        // MotionDescription,
        MotionContent,
        RequiredVote, VoteButtons
    },

    props: [],

    mixins: [motionMixin, motionObjectMixin, receiptMixin],

    data: function () {
        return {
            voteRecorded: false,
            // vote: null,

            // showButtons: false,

            titleText: {
                unVoted: 'Please vote on this motion',
                voted: 'Thank you for voting'
            }

            // isReady: true
        }
    },


    asyncComputed: {
        cardTitle: {
            get: function () {
                if (!this.isVotingAllowed) {
                    return 'The current motion is ';
                } else {
                    if (this.hasVoted) {
                        return this.titleText.voted
                    }
                    return this.titleText.unVoted
                }
            },
            default: ''
        },

        votedUponMotionIds:
            {
                get: function () {
                    //wait to make sure we have the present motion id ready to go.
                    return this.$store.getters.getMotionIdsUserVotedUpon;
                },
                // default: []
            },

        hasVoted: {
            get: function () {
                if (this.isReady) {
                    // if (!_.isUndefined(this.votedUponMotionIds) && !_.isNull(this.votedUponMotionIds)) {
                    //wait to make sure we have the present motion id ready to go.
                    // let ids = this.$store.getters.getMotionIdsUserVotedUpon;
                    return this.votedUponMotionIds.includes(this.motion.id);
                }
            },
            // default: null
        },

        isReady: {
            get: function () {

                if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                    //make sure vote records have been loaded
                    return !_.isUndefined(this.votedUponMotionIds) && !_.isNull(this.votedUponMotionIds)

                    // return !_.isUndefined(this.hasVoted) && !_.isNull(this.hasVoted)
                }
            },
            watch: ['motion']
        },

        isVotingAllowed: function () {
            return this.isReady && this.motion.isVotingAllowed;
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
                return this.motion.description;
            }
            // },
            // default: ''
        },

        originalMotion: {
            get: function () {

                if (this.isReady && this.isAmendment) {
                    // let motion = this.$store.getters.getActiveMotion;
                    // && ! _.isUndefined(this.motion.applies_to)
                    // window.console.log('j', motion, motion.appliesTo, this.motion);
                    let originalMotion = this.$store.getters.getMotionById(this.motion.appliesTo);
                    return originalMotion;
                }
            },
            // watch: 'isReady'
        },

        /**
         * If the current motion is an amendment, this will
         * get the text of the original so that the
         * display component can figure out what's new.
         * @returns {string|null|*}
         */
        originalText: function () {
            if (!this.isReady || !this.isAmendment) return ''

            if (!_.isUndefined(this.originalMotion) && !_.isNull(this.originalMotion)) {
                return this.originalMotion.content
            }
        },


        /**
         * Whether to display the aye and nay buttons
         */
        showButtons: function () {
            if (!isReadyToRock(this.hasVoted)) return false;
            return !this.hasVoted;
        }

        // vote : function(){
        //     if(isReadyToRock(this.motion)) return this.$store.getters.getCastVoteForMotion(this.motion);
        // }

    }
    ,

    computed: {
        // receipt: function () {
        //     if (isReadyToRock(this.vote)) {
        //         return this.vote.receipt;
        //     }
        // }
        // ,

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
    },

    methods: {
        /**
         * Fires when receives notification that the
         * nay button has been pressed. Sends result
         * to server.
         */
        handleNay: function () {

            this.voteRecorded = true;
            // me.showButtons = false;
            // let voteType = 'nay';
            // this.recordVote(voteType);
        },

        /**
         * Fires when receives notification that the
         * yay button has been pressed. Sends result
         * to server.
         */
        handleYay: function () {
            this.voteRecorded = true;
            // me.showButtons = false;
            // let voteType = 'yay';
            // this.recordVote(voteType);
        },

        // /**
        //  * Sends vote to server
        //  *
        //  * @param voteType
        //  */
        // recordVote: function (voteType) {
        //     let me = this;
        //
        //     let vote = new Vote(
        //         {
        //             motionId: this.motion.id,
        //
        //             //NB, the setter will translate whatever we are passing into a boolean
        //             isYay: voteType
        //         });
        //
        //     this.$store.dispatch('castMotionVote', vote).then((v) => {
        //         if (v.receipt.length > 0) {
        //             //Successfully recorded
        //             me.voteRecorded = true;
        //             me.showButtons = false;
        //         }
        //     }).catch((error) => {
        //         if (error.response) {
        //             // The request was made and the server responded with a status code
        //             // that falls out of the range of 2xx
        //             console.log(error.response.data);
        //             console.log(error.response.status);
        //             if (error.response.status === 501) {
        //                 me.voteRecorded = true;
        //                 me.showButtons = false;
        //             }
        //         }
        //
        //     });

            //
            // let url = routes.votes.recordVote(this.motion.id);
            // let data = {
            //     motionId: this.motion.id,
            //     vote: voteType,
            // };
            //
            // return new Promise((resolve, reject) => {
            //     let me = this;
            //     return Vue.axios.post(url, data)
            //         .then((response) => {
            //             console.log(response.data);
            //             me.vote = new Vote(response.data.isYay, response.data.receipt, response.data.id);
            //             me.voteRecorded = true;
            //             me.showButtons = false;
            //             //todo once receives notification that vote has been recorded, should set voteRecorded to true so inputs can be disabled.
            //
            //             me.$store.commit('addVotedUponMotion', me.motion.id);
            //             resolve();
            //         })
            //         .catch(function (error) {
            //             // error handling
            //             if (error.response) {
            //                 // The request was made and the server responded with a status code
            //                 // that falls out of the range of 2xx
            //                 console.log(error.response.data);
            //                 console.log(error.response.status);
            //                 if (error.response.status === 501) {
            //                     me.voteRecorded = true;
            //                     me.showButtons = false;
            //                 }
            //
            //             }
            //             // reject();
            //         });
            //
            // });

        // }
    },

}
</script>

<style scoped>
.motion-content {
    padding: 6em
}
</style>
