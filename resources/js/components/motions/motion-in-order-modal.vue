<!--<template>-->

<!--    <div class="modal fade"-->

<!--         v-bind:id="modalId"-->
<!--         tabindex="-1"-->
<!--         aria-hidden="true"-->
<!--         aria-labelledby="motionInOrderModalLabel"-->
<!--    >-->
<!--        <div class="modal-dialog">-->
<!--            <div class="modal-content">-->
<!--                <div class="modal-header">-->
<!--                    <h5 class="modal-title"-->
<!--                        id="motionInOrderModalLabel"-->
<!--                    >It has been moved that</h5>-->
<!--                    &lt;!&ndash;                    <button type="button"&ndash;&gt;-->
<!--                    &lt;!&ndash;                            class="btn-close"&ndash;&gt;-->
<!--                    &lt;!&ndash;                            v-on:click="handleDismiss"&ndash;&gt;-->
<!--                    &lt;!&ndash;                            aria-label="Close">&ndash;&gt;-->
<!--                    &lt;!&ndash;                        <span aria-hidden="true">&times;</span>&ndash;&gt;-->
<!--                    &lt;!&ndash;                    </button>&ndash;&gt;-->
<!--                </div>-->

<!--                <div class="modal-body">-->

<!--                    <p class="blockquote" v-html="motionText"></p>-->

<!--                    <p>-->
<!--                        <required-vote-badge :motion="motion"></required-vote-badge>-->
<!--                        <motion-type-badge :motion="motion"></motion-type-badge>-->
<!--                        <debatable-badge :motion="motion"></debatable-badge>-->
<!--                    </p>-->
<!--                    &lt;!&ndash;                    <p><strong>Requires:</strong> {{motion.requires}}</p>&ndash;&gt;-->
<!--                    &lt;!&ndash;                    <p><strong>Type: </strong>{{motion.type}}</p>&ndash;&gt;-->
<!--                </div>-->

<!--                <div class="modal-footer">-->
<!--                    <button type="button"-->
<!--                            class="btn btn-secondary"-->
<!--                            v-on:click="handleReject"-->
<!--                    >Reject-->
<!--                    </button>-->

<!--                    <button type="button"-->
<!--                            class="btn btn-primary"-->
<!--                            v-on:click="handleApprove"-->
<!--                    >Approve-->
<!--                    </button>-->

<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->


<!--</template>-->

<script>
import chairMixin from "../../mixins/chairMixin";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import RequiredVoteBadge from "./badges/required-vote-badge";
import MotionTypeBadge from "./badges/motion-type-badge";
import DebatableBadge from "./badges/debatable-badge";
import JsControlledModalParent from "../parents/js-controlled-modal-parent";

export default {
    name: "motion-in-order-modal",
    components: {DebatableBadge, MotionTypeBadge, RequiredVoteBadge},
    props: [],

    extends : JsControlledModalParent,

    mixins: [chairMixin],

    data: function () {
        return {
            leftButtonStyling: "btn-secondary",
            rightButtonStyling: "btn-primary",
            modalId: 'motionInOrderModal',
            headerText: "It has been moved that ",
            rightButtonLabel: "Approve",
            leftButtonLabel: "Reject"
        }
    },

    asyncComputed: {
        // modalId : function(){
        //     return 'motionInOrderModal';
        // },

        motion: function () {
            return this.$store.getters.nextMotionNeedingApproval;
        },

        bodyText: function () {
            if (!isReadyToRock(this.motion)) return ''
            // return this.motion.content;
            return `<p className="blockquote" >${this.motion.content}</p>`;

            /*
            dev would be nice to include this too, but would need to add the compiler parent
            <p>-->
<!--                        <required-vote-badge :motion="motion"></required-vote-badge>-->
<!--                        <motion-type-badge :motion="motion"></motion-type-badge>-->
<!--                        <debatable-badge :motion="motion"></debatable-badge>-->
<!--                    </p>-->
             */
        },

        showModal: function () {
            if (this.isOrderAuthority && isReadyToRock(this.motion)) {
                this.openModal();
                // $('#' + this.modalId).modal();
                return true;
            }

        }


    },

    computed: {},
    // mounted() {
    //     var myModal = new bootstrap.Modal(document.getElementById(this.modalId), {});
    // },

    methods: {
        // closeModal: function () {
        //     var myModalEl = document.getElementById(this.modalId);
        //     var modal = bootstrap.Modal.getInstance(myModalEl)
        //     if (isReadyToRock(modal)) {
        //         modal.hide();
        //
        //     }
        //     // $('#' + this.modalId).modal('hide');
        // },
        //
        // openModal: function () {
        //     window.console.log('showing ', this.modalId);
        //     var myModalEl = document.getElementById(this.modalId);
        //     var modal = bootstrap.Modal.getInstance(myModalEl)
        //     // if (isReadyToRock(modal)) {
        //     modal.show();
        //     // }
        //
        // },


        handleLeftClick: function () {
            this.handleReject();
        },
        handleRightClick: function () {
            this.handleApprove();
        },

        handleApprove: function () {
            this.$store.dispatch('markMotionInOrder', this.motion)
            this.closeModal();
            // $('#' + this.modalId).modal('hide');


        },
        handleReject: function () {
            this.$store.dispatch('markMotionOutOfOrder', this.motion)
            // $('#' + this.modalId).modal('hide');
            this.closeModal();
        }
    }

}
</script>

<style scoped>

</style>
