<template>

    <div class="card vote-verification">
        <div class="card-header">
            <h4 card-title>Confirm that your vote was counted</h4>

        </div>

        <div class="card-body">
            <h5 card-title> Please enter your receipt in the box and click the 'Verify vote' button.</h5>
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
            </div>
        </div>
        <!--                    <input type="text" id="receipt-entry" v-model="receipt">-->
        <!--                    <p>-->
        <!--                        <button type="button" class="btn btn-info"-->
        <!--                                v-on:click="handleClick"-->
        <!--                        >Verify vote-->
        <!--                        </button>-->
        <!--                    </p>-->


        <!--            </div>-->
        <div class="card-body">
            <div class="card-text">
                <p v-if="verificationResult"></p>

                <p>Feel free to enter fake receipts to check the legitimacy of your receipt</p>

                <div class="alert alert-success" role="alert" v-if="showGood">
                    <h4 class="alert-heading">Your receipt is valid</h4>
                    <p>The vote associated with this receipt is: <strong>{{ voteDisplay }}</strong></p>
                    <p>Receipt : {{ receipt }}</p>

                    <p class="text-right">
                        <button type="button" class="btn btn-info" v-on:click="closeAlert">Close</button>
                    </p>
                </div>

                <div class="alert alert-error" role="alert" v-if="showBad">
                    <h4 class="alert-heading">This is not a valid receipt</h4>
                    <!--            <p>todo receipt here along with meeting / motion info</p>-->
                    <p> Receipt : {{ receipt }} </p>

                    <button type="button" class="btn btn-info" v-on:click="closeAlert">Close</button>

                </div>

            </div>

        </div>
    </div>


</template>

<script>

import routes from '../../routes';
import Vote from '../../models/Vote';

export default {
    name: "vote-verification-page",
    data: function () {
        return {
            showBad: false,
            showGood: false,
            receipt: '',
            vote: null,
            sampleReceipt : '3367011432d697b81096f820e608e0e43ad3a63055692974428b4320cc4d6721'

        }
    },
    computed: {

        verificationResult: function () {
            return false;

            //return "Vote for {{voteType}} was received {{timestamp}}"
        },

        voteDisplay: function () {
            if (_.isNull(this.vote) || _.isUndefined(this.vote)) return ''

            return this.vote.voteEnglish()
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
            return new Promise((resolve, reject) => {
                let url = routes.receipts.validateReceipt();
                let payload = {receipt: receipt};

                this.$http.post(url, payload)
                    .then(function (response) {
                        if (!_.isUndefined(response.data.id)) {

                            me.vote = new Vote(response.data.is_yay);
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
