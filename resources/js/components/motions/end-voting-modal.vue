<template>

    <!-- Modal -->
    <div class="modal fade" id="endVotingModal" tabindex="-1" aria-labelledby="endVotingModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="endVotingModalLabel">End voting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p> You are about to end voting on this motion. This cannot be undone
                    </p>
                    <p>Are you sure?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No. Keep voting</button>
                    <button type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                            v-on:click="endVoting">Yes. End voting.
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
    name: "end-voting-modal",

    props: [],

    mixins: [MotionMixin, motionObjectMixin],

    data: function () {
        return {}
    },

    computed: {},

    methods: {
        endVoting: function () {
            this.$store.dispatch('endVotingOnMotion', this.motion);
        }

    }


}

</script>

<style scoped>

</style>
