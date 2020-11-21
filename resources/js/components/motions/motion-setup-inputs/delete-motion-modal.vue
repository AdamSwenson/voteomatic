<template>

    <!-- Modal -->
    <div class="modal fade"
         id="deleteMotionModal"
         tabindex="-1"
         aria-labelledby="deleteMotionModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteMotionModalLabel">Delete Motion</h5>
                    <button type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p> You are about to permanently delete this motion and any votes associated with it. This cannot be
                        undone
                    </p>
                    <p>Are you sure?</p>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal"
                    >No</button>

                    <button type="button"
                            class="btn btn-primary"
                            data-dismiss="modal"
                            v-on:click="handleClick"
                    >Yes. Delete it.</button>

                </div>
            </div>
        </div>
    </div>

</template>

<script>

import MotionMixin from '../../../mixins/motionMixin'

/**
 * Note, this will require that the delete-motion-button is
 * included elsewhere on the page. They are linked via  bootstrap
 * using the data-dismiss=modal attribute. They are not linked
 * by vue or vuex events.
 */
export default {
    name: "delete-motion-modal",

    props: [],

    mixins: [MotionMixin],

    data: function () {
        return {}
    },

    computed: {},

    methods: {
        handleClick: function () {

            let me = this;

            //First we create and store a new motion from the
            //provided template
            let p = this.$store.dispatch('deleteMotion', me.motion)
                .then(function () {
                });
        }

    }


}

</script>

<style scoped>

</style>
