<template>

    <!-- Modal -->
    <div class="modal fade" id="abortVotingModal" tabindex="-1" aria-labelledby="abortVotingModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="abortVotingModalLabel">Abort voting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> You are about to cancel voting on this motion.</p>
                    <p>This will delete all votes cast so far and prevent any additional votes</p>
                    <p>This cannot be undone</p>
                    <p>Are you sure?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No. Keep voting</button>
                    <button type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                            v-on:click="abortVoting">Yes. Abort voting.
                    </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

import MotionMixin from '../../mixins/motionStoreMixin';

import motionObjectMixin from "../../mixins/motionObjectMixin";

/**
 * Note, this will require that the end-voting-button is
 * included elsewhere on the page. They are linked via  bootstrap
 * using the data-bs-dismiss=modal attribute. They are not linked
 * by vue or vuex events.
 */
export default {
    name: "abort-voting-modal",

    props: [],

    mixins: [MotionMixin, motionObjectMixin],

    data: function () {
        return {}
    },

    computed: {},

    methods: {
        abortVoting: function () {
            this.$store.dispatch('abortVotingOnMotion', this.motion);
        }

    }


}

</script>

<style scoped>

</style>
