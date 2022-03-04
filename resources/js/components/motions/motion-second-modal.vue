<!--<template>-->
    <!--    <div class="modal fade"-->
    <!--         v-if="showModal"-->
    <!--         v-bind:id="modalId"-->
    <!--         tabindex="-1"-->
    <!--         v-bind:aria-labelledby="labelId"-->
    <!--    >-->
    <!--        <div class="modal-dialog">-->
    <!--            <div class="modal-content">-->
    <!--                <div class="modal-header">-->
    <!--                    <h5 class="modal-title"-->
    <!--                        v-bind:id="labelId"-->
    <!--                    >It has been moved that</h5>-->
    <!--                    <button type="button"-->
    <!--                            class="btn-close"-->
    <!--                            v-on:click="handleDismiss"-->
    <!--                            aria-label="Close">-->
    <!--                        <span aria-hidden="true">&times;</span>-->
    <!--                    </button>-->
    <!--                </div>-->

    <!--                <div class="modal-body">-->

    <!--                    <p class="blockquote" v-html="motionText"></p>-->

    <!--                    <p class="second-instruction text-muted">Seconding a motion only implies that you-->
    <!--                        believe the motion to be worth discussing. It does not imply endorsement.</p>;-->

    <!--                </div>-->

    <!--                <div class="modal-footer">-->
    <!--                    <button type="button"-->
    <!--                            class="btn btn-secondary"-->
    <!--                            v-on:click="handleDismiss"-->
    <!--                    >Dismiss-->
    <!--                    </button>-->

    <!--                    <button type="button"-->
    <!--                            class="btn btn-primary"-->
    <!--                            data-bs-dismiss="modal"-->
    <!--                            v-on:click="handleSecond"-->
    <!--                    >{{ buttonLabel }}-->
    <!--                    </button>-->

    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->

<!--</template>-->

<script>
import ModalParent from "../parents/js-controlled-modal-parent";
import motionSecondMixin from "../../mixins/motionSecondMixin";
import {isReadyToRock} from "../../utilities/readiness.utilities";

export default {
    name: "motion-second-modal",

    props: [],

    extends: ModalParent,

    mixins: [motionSecondMixin],

    data: function () {
        return {
            leftButtonStyling: "btn-danger",
            rightButtonStyling: "btn-success",
            modalId: "motionSecondModal",
            headerText: "It has been moved that",
            rightButtonLabel:"I second this motion",
            leftButtonLabel:  "Dismiss"
        }
    },


    asyncComputed: {
        /**
         * Controls whether the modal displays
         * @returns {boolean}
         */
        showModal: {
            get: function () {
                //Make sure it has loaded and has the value true
                if (isReadyToRock(this.isMotionPendingSecond) && this.isMotionPendingSecond === true) {
                    // window.console.log('show');
                    this.openModal();
                    return true;
                }else {
                    this.closeModal();
                    return false;
                }
            },
            watch : ['isMotionPendingSecond']
        },

        bodyText: function () {
            return `<p class="blockquote">${this.motionText}</p>

            <p class="second-instruction text-muted">Seconding a motion implies that you
                believe the motion to be worth discussing. It does not imply endorsement.</p>
            `;

        },

        motionText: function () {
            if (! isReadyToRock(this.motionPendingSecond) || !this.showModal) return ''
            return this.motionPendingSecond.content;
        },


    },

    computed: {},

    methods: {
        handleDismiss: function () {
            this.$store.dispatch('resetMotionPendingSecond');
        },

        handleSecond: function () {
            this.$store.dispatch('secondMotion', this.motionPendingSecond);
        },

        handleLeftClick: function () {
            //They have clicked dismiss
            this.handleDismiss();
            this.closeModal();
        },
        handleRightClick: function () {
           //They have clicked second
            this.handleSecond();
            this.closeModal();
        },

    }

}
</script>

<style scoped>
.second-instruction {
    margin-top: 2em;
}
</style>
