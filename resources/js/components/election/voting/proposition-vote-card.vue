<template>
    <div class="card vote-page">

        <div class="card-header">
            <h4 class="card-title">{{ cardTitle }}</h4>
        </div>

        <div class="vote-area card-body" >
            <div class="contentArea" v-html="motionContent"></div>
            </div>

        <div class="card-body">
            <div class="descriptionArea" v-html="motionDescription"></div>

<!--            <div class="text-center">-->
<!--                <motion-text-display-->
<!--                    :motion="motion"-->
<!--                ></motion-text-display>-->
<!--            </div>-->
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h3 class="card-title">Pro</h3>
                <p class="card-text">[Insert]</p>
                </div>

                <div class="col">
                    <h3 class="card-title">Con</h3>
                    <p class="card-text">[Insert]</p>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col">
                    <required-vote :motion="motion"></required-vote>

                    <required-vote-badge :motion="motion"></required-vote-badge>

                </div>
                <div class="col">

                </div>

                <div class="col">
<!--                    <p class="motionDescription text-muted">{{ motionDescription }}</p>-->

                </div>
            </div>
        </div>


        <div class="card-body">
<!--            <div class="grid">-->
            <div class="row">
<!--                <div class="g-col-4">-->
                <div class="col-3 text-end">
                    <button type="button"
                            class="btn btn-lg "
                            v-bind:class="yayStyling"
                            v-on:click="handleYay"
                    >Aye
                    </button>
                </div>

                <div class="col-3"></div>
                <div class="col-3"></div>
<!--                <div class="g-col-4"></div>-->
<!--                <div class="g-col-4">-->
                <div class="col-3 text-start">
                    <button type="button"
                            class="btn btn-lg "
                            v-on:click="handleNay"
                            v-bind:class="nayStyling"
                    >Nay
                    </button>
                </div>

            </div>

        </div>

        <navigation-footer></navigation-footer>

    </div>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import Vote from "../../../models/Vote";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import ModeMixin from "../../../mixins/modeMixin";
import MotionTextDisplay from "../../motions/text-display/motion-text-display";
import RequiredVote from "../../text-display/required-vote";
import RequiredVoteBadge from "../../motions/badges/required-vote-badge";
import NavigationFooter from "../voter/navigation/navigation-footer";

export default {
    name: "proposition-vote-card",
    components: {NavigationFooter, RequiredVoteBadge, RequiredVote, MotionTextDisplay},
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin, ModeMixin],


    data: function () {
        return {
            cnt : 0
        }
    },

    asyncComputed: {

        cardTitle: {
            get: function () {
                if (!isReadyToRock(this.motion, 'info') || !isReadyToRock(this.motion.info.name)) return ''

                return this.motion.info.name;
            },
            default: ''
        },

        /**
         * If the voter has selected yay or nay on
         * this proposition, this returns it
         */
        selectedVote: {
            get: function () {
                if (isReadyToRock(this.motion)) {
                    return this.$store.getters.getPropositionVoteForMotion(this.motion);
                }
            },
            watch: ['cnt']
        },

        isReady: function () {
            return isReadyToRock(this.motion);
        },
        //     get: function () {
        //
        //         if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
        //             //make sure vote records have been loaded
        //             return !_.isUndefined(this.votedUponMotionIds) && !_.isNull(this.votedUponMotionIds)
        //
        //             // return !_.isUndefined(this.hasVoted) && !_.isNull(this.hasVoted)
        //         }
        //     },
        //     watch: ['motion']
        // },

        // isVotingAllowed: function () {
        //     return this.isReady && this.motion.isVotingAllowed;
        // },

        motionContent: function () {
            if (!isReadyToRock(this.motion)) return ''
            return this.motion.content;
        },

        motionDescription: function () {
            if (!isReadyToRock(this.motion)) return ''

            return this.motion.description;
        },

        nayStyling: function () {
            if (isReadyToRock(this.selectedVote) && this.selectedVote.isYay === true) return ' btn-outline-danger '
            return ' btn-danger ';
        },

        yayStyling: function () {
            if (isReadyToRock(this.selectedVote) && this.selectedVote.isYay === false) return ' btn-outline-success '
            return ' btn-success ';
        }


    },

    computed: {},

    methods: {

        handleNay: function () {

            let vote = new Vote(
                {
                    motionId: this.motion.id,
                    isYay: false
                });
            window.console.log('nay', vote);
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
            window.console.log('yay', vote);
            this.recordVote(vote);

        },

        recordVote : function(vote){
            let me = this;
            this.$store.dispatch('storePropositionVote', vote).then(() => {
                me.cnt += 1;
            });
        }
    }

}
</script>

<style scoped>

</style>
