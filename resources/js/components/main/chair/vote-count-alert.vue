<template>
    <div class="alert alert-dismissible fade show alert-primary"
         v-if="show"
         id="vote-count-alert"
         role="alert"
    >
        <p>
            <strong>Votes cast:</strong>  {{votesCast}}
        </p>

        <p><strong>Outstanding: </strong> {{votesOutstanding}}</p>

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
export default {
    name: "vote-count-alert",

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
