<template>
    <div class="card vote-page">

        <div class="card-header">
            <h1 class="card-title">{{ cardTitle }}</h1>
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
                <div class="col-md-6">
                    <h3 class="card-title">Pro</h3>
                <p class="card-text">The Executive Committee shall set the agenda for meetings of the Senate and meetings of the Faculty.
                    They shall review all policy recommendations, monitor membership, and oversee activities of the Standing and Advisory Committees.
                    They shall oversee and conduct the nomination process and elections for the Senate and the Faculty.
                    They shall act for the Faculty, the Senate and the Standing Committees of the Senate on those matters requiring Faculty action or
                    consultation during the intersession, special sessions or summer months.</p>

                    <p class="card-text">Sixty percent or more of the faculty are Lecturers.  This by-law change will add a dedicated seat to the Executive Committee
                        for a Lecturer.  Voting yes on this by-law change will add the Lecturer’s voice to faculty decisions and will ensure representation of more than
                        60 percent of the faculty.</p>

                </div>

                <div class="col-md-6">
                    <h3 class="card-title">Con</h3>
                    <p class="card-text">All senators, lecturer and tenure track, are currently eligible to seek election to the Executive Committee (SEC). For tenure track senators,
                        the SEC’s heavy workload is part of their job’s service component. For lecturer senators, the massive time commitment is uncompensated but unpressured. With this
                        Bylaws change, the 9 lecturer senators would face pressure to stand for election and do uncompensated work; pressure from which their tenure track colleagues are immune.  </p>
                </div>
            </div>
        </div>

        <div class="card-body">
<!--            <div class="row">-->
<!--                <div class="col">-->
                    <required-vote :motion="motion"></required-vote>

                    <required-vote-badge :motion="motion"></required-vote-badge>

<!--                </div>-->
<!--                <div class="col">-->

<!--                </div>-->

<!--                <div class="col">-->
<!--&lt;!&ndash;                    <p class="motionDescription text-muted">{{ motionDescription }}</p>&ndash;&gt;-->

<!--                </div>-->
<!--            </div>-->
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
                    >Yes
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
                    >No
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
