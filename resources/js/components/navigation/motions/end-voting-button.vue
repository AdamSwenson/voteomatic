<template>
    <!--    <button v-bind:class="styling"-->
    <!--            v-on:click="endVoting"-->
    <!--    >End voting [todo warnings]-->
    <!--    </button>-->
    <div class="end-voting-control">
        <!-- Button trigger modal -->
        <button type="button"
                v-bind:class="styling"
                data-toggle="modal"
                data-target="#endVotingModal">
            End voting
        </button>

        <!-- Modal -->
        <div class="modal fade" id="endVotingModal" tabindex="-1" aria-labelledby="endVotingModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="endVotingModalLabel">End voting</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p> You are about to end voting on this motion. This cannot be undone
                        </p>
                        <p>Are you sure?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No. Keep voting</button>
                        <button type="button"
                                class="btn btn-primary"
                                data-dismiss="modal"
                                v-on:click="endVoting">Yes. End voting.
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "end-voting-button",
    props: ['motion'],

    asyncComputed: {

        styling: function () {
            let style = "btn btn-danger ";
            if (_.isUndefined(this.motion) || _.isNull(this.motion)) return style;

            if (this.isComplete) {
                style = 'btn btn-danger disabled';
            }
            return style;
        },

        isComplete: function () {
            if (!_.isUndefined(this.motion) || !_.isNull(this.motion)) {
                return this.motion.isComplete;
            }

        }
    },


    methods: {
        endVoting: function () {
            this.$store.dispatch('endVotingOnMotion', this.motion);
        }

    }
}
</script>

<style scoped>

</style>
