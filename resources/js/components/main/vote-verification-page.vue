<template>

    <div class="card vote-verification router-tab-touching-card">
        <div class="card-header">
            <h4 card-title>Confirm that your vote was counted</h4>
        </div>

        <div class="card-body">
            <h5 card-title> To check that your vote was counted, enter a receipt in the box and click the 'Verify vote'
                button.</h5>
            <div class="card-text">

                <div class="input-group mb-3">
                    <input type="text"
                           id="receiptEntry"
                           v-model="receipt"
                           class="form-control"
                           v-bind:placeholder="placeholder"
                           aria-label="Verification input"
                           aria-describedby="verificationSubmit"
                           data-test="receiptEntry"
                    >
                    <div class="input-group-append">
                        <button
                            class="btn btn-primary"
                            type="button"
                            id="verificationSubmit"
                            v-if="enableButton"
                            aria-label="Submit the receipt for verification"
                            v-on:click="handleClick"
                            data-test="verificationSubmit"
                        >Verify vote
                        </button>
                    </div>
                </div>

                <p class="card-text">Feel free to enter fake receipts to demonstrate that this is actually checking your
                    receipts</p>

                <p class="card-text">For more information about how the voteomatic keeps your vote
                    anonymous, please see <a href="https://github.com/AdamSwenson/voteomatic#anonymity">https://github.com/AdamSwenson/voteomatic#anonymity</a>
                </p>


            </div>

            <div class="card-text">
                <!--                <p v-if="verificationResult"></p>-->

                <valid-election-receipt-alert v-if="isElection && showGood"
                                              :receipt="receipt"
                                              :show-vote-content="showVoteContent"
                                              :office-name="officeName"
                                              :candidate-name="candidateName"
                                              v-on:closeAlert="handleCloseAlert"
                ></valid-election-receipt-alert>

                <valid-motion-receipt-alert v-if="! isElection && showGood"
                                            :receipt="receipt"
                                            :vote-display="voteDisplay"
                                            v-on:closeAlert="handleCloseAlert"
                ></valid-motion-receipt-alert>

                <invalid-receipt-alert v-if="showBad"
                                       :receipt="receipt"
                                       v-on:closeAlert="handleCloseAlert"
                ></invalid-receipt-alert>

            </div>

        </div>

        <div class="card-body" v-if="showReceipts">
            <p class="card-text">The receipts below are temporarily stored on your browser. If you refresh the page,
                it will no longer be possible to retrieve your receipts since your user id is not
                linked to them in the database. Use the buttons below to download a list of your
                receipts for safekeeping</p>

            <receipt-list-area></receipt-list-area>

        </div>

        <div class="card-footer" v-if="showReceipts">
            <span class="me-3"><copy-button></copy-button></span>
            <download-receipts-button></download-receipts-button>

        </div>
    </div>


</template>

<script>

import routes from '../../routes';
import Vote from '../../models/Vote';
import ReceiptListArea from "../vote-verification/receipt-list-area";
import CopyButton from "../vote-verification/copy-receipts-button";
import DownloadReceiptsButton from "../vote-verification/download-receipts-button";
import ModeMixin from "../../mixins/modeMixin";
import SettingsMixin from "../../mixins/settingsMixin";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import InvalidReceiptAlert from "../vote-verification/invalid-receipt-alert.vue";
import ValidElectionReceiptAlert from "../vote-verification/valid-election-receipt-alert.vue";
import ValidMotionReceiptAlert from "../vote-verification/valid-motion-receipt-alert.vue";

export default {
    name: "vote-verification-page",
    components: {
        ValidMotionReceiptAlert,
        ValidElectionReceiptAlert, InvalidReceiptAlert, DownloadReceiptsButton, CopyButton, ReceiptListArea
    },

    mixins: [ModeMixin, SettingsMixin],

    data: function () {
        return {
            showBad: false,
            showGood: false,
            receipt: '',
            vote: null,
            placeholder: "Enter your receipt here",

        }
    },

    watch: {
        receipt(v) {
            window.console.log('vote-verification-page', 'watch', 166, v);
            this.closeAlerts();
        }
    },

    computed: {

        allVotes: function () {
            return this.$store.getters.getUsersCastVotes;
        },

        /**
         * Only show the button if they have entered a
         * receipt in the box
         * @returns {boolean}
         */
        enableButton: function () {
            return this.receipt.length > 0;
        },

        showReceipts: function () {
            return isReadyToRock(this.allVotes) && this.allVotes.length > 0;
        },

        /**
         * Whether to show how the user voted.
         * Primarily for elections
         */
        showVoteContent: function () {
            if (!isReadyToRock(this.settings)) return false;
            return this.settings.settings.reveal_ballot_contents;
        },

        officeName: function () {
            if (!this.showVoteContent) return ''
            if (!isReadyToRock(this.vote)) return ''
            let office = this.$store.getters.getMotionById(this.vote.motionId);
            window.console.log('vote-verification-page', 'officeName', 152, office);
            return office.content;
        },

        /**
         * If showing the content of the vote, the name
         * of the candidate who was selected
         *
         * @returns {*|string}
         */
        candidateName: function () {
            if (!this.showVoteContent) return ''
            if (!isReadyToRock(this.vote)) return ''
            let candidate = this.$store.getters.getCandidateById(this.vote.candidateId);
            // window.console.log('vote-verification-page', 'candidateName', 164, candidate[0].name);
            return candidate[0].name;
        },


        // verificationResult: function () {
        //     return false;
        //
        //     //return "Vote for {{voteType}} was received {{timestamp}}"
        // },

        voteDisplay: function () {
            if (_.isNull(this.vote) || _.isUndefined(this.vote)) return ''

            return this.vote.voteDisplayEnglish()
        }

    },

    methods: {
        /**
         * Hides all validation alerts and
         * resets the receipt and vote fields.
         */
        resetAll: function () {
            this.receipt = '';
            this.vote = null;
        },

        /**
         * Hides any showing alerts.
         * This is separate from resetting because
         * we need to close alerts without changing the
         * receipt / vote in some cases. E.g., when the text
         * in the input box has changed.
         */
        closeAlerts: function () {
            this.showGood = false;
            this.showBad = false;
        },


        handleCloseAlert: function () {
            this.closeAlerts();
            this.resetAll();
        },

        // handleValid: function () {
        //     this.showGood = true;
        // },
        // handleNotValid: function () {
        //     this.showBad = true;
        // },

        verifyReceipt: function (receipt) {
            let me = this;
            this.closeAlerts();
            return new Promise((resolve, reject) => {
                let url = routes.receipts.validateReceipt();
                let payload = {receipt: receipt};

                this.$http.post(url, payload, {
                    validateStatus: function (status) {
                        if (status === 422) {
                            window.console.log('vote-verification-page', 'validateStatus', 218, status);
                            me.$store.dispatch('showServerProvidedMessage', {'message': "Please enter your receipt in the box below"})
                            return false;
                        }
                        return true;
                    }
                })
                    .then(function (response) {
                        if (!_.isUndefined(response.data.id)) {

                            if (me.isElection) {
                                me.vote = new Vote({
                                    receipt: response.data.receipt,
                                    motionId: response.data.motion_id,
                                    candidateId: response.data.candidate_id
                                });

                            } else {
                                //The is_yay prop being undefined will report the
                                //receipt as invalid. The error will be caught below
                                me.vote = new Vote({
                                    receipt: response.data.receipt,
                                    motionId: response.data.motion_id,
                                    isYay: response.data.is_yay
                                });
                            }

                            //store validated vote centrally, only if
                            //the motion is from this event
                            //(otherwise will have receipts listed with no associated motion)
                            // if(me.$store.getters.hasVotedOnMotion(me.motionId)){
                            me.$store.commit('addCastVote', me.vote);
                            // }

                            me.showGood = true;

                        } else {
                            me.showBad = true;
                        }
                    }).catch(function (error) {
                    me.showBad = true;

                });
            });

        },

        handleClick: function () {
            this.verifyReceipt(this.receipt);

        }
    }

}
</script>

<style scoped>

</style>
