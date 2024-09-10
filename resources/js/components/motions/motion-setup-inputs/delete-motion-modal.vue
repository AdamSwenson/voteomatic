<!--<template>-->

<!--    &lt;!&ndash; Modal &ndash;&gt;-->
<!--    <div class="modal fade"-->
<!--         id="deleteMotionModal"-->
<!--         tabindex="-1"-->
<!--         aria-labelledby="deleteMotionModalLabel"-->
<!--         aria-hidden="true">-->
<!--        <div class="modal-dialog">-->
<!--            <div class="modal-content">-->
<!--                <div class="modal-header">-->
<!--                    <h5 class="modal-title" id="deleteMotionModalLabel">Delete Motion</h5>-->
<!--                    <button-->
<!--                        type="button"-->
<!--                        class="btn-close"-->
<!--                        data-bs-dismiss="modal"-->
<!--                        aria-label="Close"></button>-->
<!--                </div>-->

<!--                <div class="modal-body">-->
<!--                    <p> You are about to permanently delete this motion and any votes associated with it. This cannot be-->
<!--                        undone-->
<!--                    </p>-->
<!--                    <p>Are you sure?</p>-->
<!--                </div>-->

<!--                <div class="modal-footer">-->
<!--                    <button type="button"-->
<!--                            class="btn btn-secondary"-->
<!--                            data-bs-dismiss="modal"-->
<!--                    >No-->
<!--                    </button>-->

<!--                    <button type="button"-->
<!--                            class="btn btn-primary"-->
<!--                            data-bs-dismiss="modal"-->
<!--                            v-on:click="handleClick"-->
<!--                    >Yes. Delete it.-->
<!--                    </button>-->

<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->

<!--</template>-->

<script>
import ModalParent from "../../parents/modal-parent";
import MotionStoreMixin from '../../../mixins/motionStoreMixin'

/**
 * Note, this will require that the delete-motion-button is
 * included elsewhere on the page. They are linked via  bootstrap
 * using the data-bs-dismiss=modal attribute. They are not linked
 * by vue or vuex events.
 */
export default {
    name: "delete-motion-modal",

    props: [],

    extends : ModalParent,
    mixins: [MotionStoreMixin],

    data: function () {
        return {
            modalId: 'deleteMotionModal',
            buttonLabel: 'Yes. Delete it.',
            modalSecondaryText: ''

        }
    },

    asyncComputed: {
        modalText: function () {
            let type = this.motion.type === 'proposition' ? 'proposition' : 'motion';
            return `<p> You are about to permanently delete this ${type} and any votes associated with it.</p>
                    <p>This cannot be undone</p>
                    <p>Are you sure?</p>`
        },

        modalTitle: function () {
            if (this.motion.type === 'proposition') return 'Delete Proposition';
            return 'Delete Motion'
        }
    },

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
