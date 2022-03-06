å<template>
    <div class="proposition-result-row" v-bind:class="styling">
        <h4>{{ propositionName }}</h4>
        <dl class="row">
            <dt class="col-sm-3">Votes received</dt>
            <dd class="col-sm-9">{{ totalVote }}</dd>

            <dt class="col-sm-3">Share of votes cast</dt>
            <dd class="col-sm-9">{{ voteShare }}%</dd>
        </dl>
    </div>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "proposition-result-row",

    props: ['result'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {

        propositionName: function () {
            if (isReadyToRock(this.result)) return this.result.propositionName;
        },

        totalVote: function () {
            if (isReadyToRock(this.result)) return this.result.voteCount;
        },

        styling: function () {
            if (isReadyToRock(this.result)) {

                if(this.result.isWinner) return 'bg-success';

                if(this.result.isRunoffParticipant) return 'bg-warning';
                //
                // switch (this.result) {
                //
                //     case this.result.isWinner:
                //         return 'bg-success';
                //         break;
                //
                //     case this.result.isRunoffParticipant:
                //         return 'bg-warning';
                //         break;
                //
                //     default:
                //         return ''
                // }
            }
        },

        voteShare: function () {
            if (isReadyToRock(this.result)) {
                return this.result.voteShareAsPercentage;
            }
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
