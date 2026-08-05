<template>
    <section class="alert alert-dismissible fade show alert-primary"
         v-if="show"
         id="vote-count-alert"
         role="alert"
    >
        <h2 class="h5 mb-3">Voting is open</h2>

        <div class="row align-items-center">
            <div class="col">
                <p class="mb-1" aria-live="polite">
                    <strong>{{ votesCast }} of {{ memberCount }} members have voted</strong>
                </p>

                <p class="mb-0">{{ votesOutstanding }} vote<span v-if="votesOutstanding !== 1">s</span> remaining</p>

            </div>

            <div class="col">
                <div class="d-grid gap-2 mb-2">
<!--                    NB, the modal will need to have been included on any page where this alert is used-->
                    <end-voting-button :motion="motion"></end-voting-button>
                    <end-voting-modal></end-voting-modal>
                </div>
                <div class="d-grid gap-2 mb-2">
                    <!--                    NB, moved the modal back to being included here because of problems when on different card-->
                    <abort-voting-button :motion="motion"></abort-voting-button>
                    <abort-voting-modal></abort-voting-modal>

                </div>
            </div>

        </div>


        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
        >
            <span class="visually-hidden" aria-hidden="true">&times;</span>
        </button>
    </section>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import ChairMixin from "../../../mixins/chairMixin";
import MotionMixin from "../../../mixins/motionStoreMixin";
import EndVotingButton from "../../motions/end-voting-button";
import EndVotingModal from "../../motions/end-voting-modal";
import AbortVotingButton from "../../motions/abort-voting-button.vue";
import AbortVotingModal from "../../motions/abort-voting-modal.vue";

export default {
    name: "vote-count-alert",
    components: {AbortVotingModal, AbortVotingButton, EndVotingModal, EndVotingButton},
    props: [],

    mixins: [ChairMixin, MotionMixin],

    data: function () {
        return {}
    },


    computed: {
        // asyncComputed: {
        isVotingAllowed: function () {
            return isReadyToRock(this.motion) && this.motion.isVotingAllowed;
        },

        show: function () {
            return this.isChair && !this.isComplete && this.isVotingAllowed;
        },

        votesCast: function () {
            let cnt = this.$store.getters.getCastVotesCount;
            if (isReadyToRock(cnt)) return cnt;
            return '-';
        },

        votesOutstanding: function () {
            let cnt = this.$store.getters.getMemberCount;
            if (isReadyToRock(cnt) && isReadyToRock(this.votesCast)) return Math.max(cnt - this.votesCast, 0);
            return '-';
        },

        memberCount: function () {
            let cnt = this.$store.getters.getMemberCount;
            if (isReadyToRock(cnt)) return cnt;
            return '-';
        }

    },


    methods: {}

}
</script>

<style scoped>

</style>
