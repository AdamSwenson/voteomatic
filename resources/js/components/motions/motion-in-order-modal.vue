<template>

    <div class="modal fade"

         v-bind:id="modalId"
         tabindex="-1"
         aria-hidden="true"
         aria-labelledby="motionInOrderModalLabel"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="motionInOrderModalLabel"
                    >It has been moved that</h5>
                    <!--                    <button type="button"-->
                    <!--                            class="close"-->
                    <!--                            v-on:click="handleDismiss"-->
                    <!--                            aria-label="Close">-->
                    <!--                        <span aria-hidden="true">&times;</span>-->
                    <!--                    </button>-->
                </div>

                <div class="modal-body">

                    <p class="blockquote" v-html="motionText"></p>

                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            v-on:click="handleReject"
                    >Reject
                    </button>

                    <button type="button"
                            class="btn btn-primary"
                            v-on:click="handleApprove"
                    >Approve
                    </button>

                </div>
            </div>
        </div>
    </div>


</template>

<script>
import chairMixin from "../../mixins/chairMixin";
import {isReadyToRock} from "../../utilities/readiness.utilities";

export default {
    name: "motion-in-order-modal",

    props: [],

    mixins: [chairMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        modalId : function(){
            return 'motionInOrderModal';
        },

        motion: function () {
            return this.$store.getters.nextMotionNeedingApproval;
        },

        motionText: function () {
            if (!isReadyToRock(this.motion)) return ''
            return this.motion.content;
        },

        showModal: function () {
            if(this.isOrderAuthority && isReadyToRock(this.motion)){
                $('#' + this.modalId).modal();
                return true;
            }

        }


    },

    computed: {},

    methods: {

        handleApprove: function () {
            this.$store.dispatch('markMotionInOrder', this.motion)
            $('#' + this.modalId).modal('hide');


        },
        handleReject: function () {
            this.$store.dispatch('markMotionOutOfOrder', this.motion)
            $('#' + this.modalId).modal('hide');

        }
    }

}
</script>

<style scoped>

</style>
