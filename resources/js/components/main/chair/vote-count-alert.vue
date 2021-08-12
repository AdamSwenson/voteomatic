<template>
    <div class="alert alert-dismissible fade show alert-primary"
         v-if="show"
         id="vote-count-alert"
         role="alert"
    >
        <div class="row">
        <div class="col">
            <p>
            <strong>Votes cast:</strong>  {{votesCast}}
        </p>

        <p><strong>Outstanding: </strong> {{votesOutstanding}}</p>
        </div>
            <div class="col">
                <end-voting-button :motion="motion"></end-voting-button>
                <end-voting-modal></end-voting-modal>
            </div>
        </div>
        <button type="button"
                class="close"
                data-dismiss="alert"
                aria-label="Close"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import ChairMixin from "../../../mixins/chairMixin";
import MotionMixin from "../../../mixins/motionStoreMixin";
import EndVotingButton from "../../motions/end-voting-button";
import EndVotingModal from "../../motions/end-voting-modal";
export default {
    name: "vote-count-alert",
    components: {EndVotingModal, EndVotingButton},
    props: [],

    mixins: [ChairMixin, MotionMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        isVotingAllowed: function(){
            return isReadyToRock(this.motion) && this.motion.isVotingAllowed;
        },

        show: function(){
            return this.isChair && ! this.isComplete && this.isVotingAllowed;
        },

        votesCast: function(){
            let cnt = this.$store.getters.getCastVotesCount;
            if(isReadyToRock(cnt)) return cnt;
            return '-';
        },

        votesOutstanding : function(){
            let cnt = this.$store.getters.getMemberCount;
            if(isReadyToRock(cnt)) return cnt;
            return '-';
        }

    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
