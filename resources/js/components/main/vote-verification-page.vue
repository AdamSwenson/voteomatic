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
                           id="receipt-entry"
                           v-model="receipt"
                           class="form-control"
                           v-bind:placeholder="sampleReceipt"
                           aria-label="Verification input"
                           aria-describedby="button-addon2">
                    <div class="input-group-append">
                        <button
                            class="btn btn-primary"
                            type="button"
                            id="button-addon2"
                            v-on:click="handleClick"
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
            <!--        </div>-->


            <!--            </div>-->
            <!--        <div class="card-body">-->
            <div class="card-text">
                <p v-if="verificationResult"></p>


                <div class="alert alert-success" role="alert" v-if="showGood">
                    <h4 class="alert-heading">This receipt is valid</h4>
                    <h5 class="alert-heading">The database contains a record of this vote</h5>
                    <div class="ms-5 mt-3" v-if="isElection && showVoteContent">
                        <h4 class="h4">{{ officeName }}</h4>
                        <ul>
                            <li>{{ candidateName }}</li>
                        </ul>
                    </div>

                    <p v-if="! isElection ">The vote associated with this receipt is: <strong>{{ voteDisplay }}</strong>
                    </p>

                    <p>Receipt : {{ receipt }}</p>

                    <p class="text-end">
                        <button type="button" class="btn btn-info" v-on:click="closeAlert">Close</button>
                    </p>
                </div>

                <div class="alert alert-danger" role="alert" v-if="showBad">
                    <h4 class="alert-heading">This is not a valid receipt</h4>
                    <p> Receipt : {{ receipt }} </p>
                    <p class="text-end">
                        <button type="button" class="btn btn-info" v-on:click="closeAlert">Close</button>
                    </p>
                </div>

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
            <copy-button></copy-button>
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

export default {
    name: "vote-verification-page",
    components: {DownloadReceiptsButton, CopyButton, ReceiptListArea},

    mixins: [ModeMixin, SettingsMixin],

    data: function () {
        return {
            showBad: false,
            showGood: false,
            receipt: '',
            vote: null,
            sampleReceipt: '3367011432d697b81096f820e608e0e43ad3a63055692974428b4320cc4d6721'

        }
    },

    asyncComputed: {
        allVotes: function () {
            return this.$store.getters.getUsersCastVotes;
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
        }

    },

    computed: {

        verificationResult: function () {
            return false;

            //return "Vote for {{voteType}} was received {{timestamp}}"
        },

        voteDisplay: function () {
            if (_.isNull(this.vote) || _.isUndefined(this.vote)) return ''

            return this.vote.voteDisplayEnglish()
        }

    },

    methods: {
        closeAlert: function () {
            this.showBad = false;
            this.showGood = false;
        },

        handleValid: function () {
            this.showGood = true;
        },
        handleNotValid: function () {
            this.showBad = true;
        },

        verifyReceipt: function (receipt) {
            let me = this;
            this.closeAlert();
            return new Promise((resolve, reject) => {
                let url = routes.receipts.validateReceipt();
                let payload = {receipt: receipt};

                this.$http.post(url, payload)
                    .then(function (response) {
                        if (!_.isUndefined(response.data.id)) {

                            if (me.isElection) {
                                me.vote = new Vote({
                                    motionId: response.data.motion_id,
                                    candidateId: response.data.candidate_id
                                });

                            } else {
                                //The is_yay prop being undefined will report the
                                //receipt as invalid. The error will be caught below
                                me.vote = new Vote({isYay: response.data.is_yay});
                            }

                            me.showGood = true;

                        } else {
                            me.showBad = true;
                        }
                    }).catch(function (error) {
                    window.console.log(error);
                    me.showBad = true;
                });
            });

        },

        handleClick: function () {
            this.verifyReceipt(this.receipt);

            // alert('something will happen');
        }
    }

}
</script>

<style scoped>

</style>
