<template>

    <!-- Modal -->
    <div class="view-full-text-modal modal fade"
         v-bind:id="modalId"
         tabindex="-1"
         v-bind:aria-labelledby="ariaLabel"

    >
<!--        aria-hidden="true"-->
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        v-bind:id="ariaLabel"
                    >{{ title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
<!--                        <span aria-hidden="true">&times;</span>-->
                    </button>
                </div>
                <div class="modal-body">
                    <div class="body-text">
                        <component
                            v-bind:is="displayComponent"
                            :motion="motion"
                        ></component>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-primary"
                            data-bs-dismiss="modal"
                    >Close</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

import MotionMixin from '../../../mixins/motionStoreMixin';

import motionObjectMixin from "../../../mixins/motionObjectMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import ResolutionAmendmentTextDisplay from "../text-display/resolution-amendment-text-display.vue";
import AmendmentTextDisplay from "../text-display/amendment-text-display.vue";
import ResolutionTextDisplay from "../text-display/resolution-text-display.vue";

/**
 * Used when there is truncated amendment text to allow the user
 * to view the whole amendment
 *
 * Note, this will require that the view-full-text-button is
 * included elsewhere on the page. They are linked via  bootstrap
 * using the data-bs-dismiss=modal attribute. They are not linked
 * by vue or vuex events.
 */
export default {
    name: "view-full-text-modal",
    components: {AmendmentTextDisplay, ResolutionAmendmentTextDisplay},

    props: ['motion'],

    mixins: [motionObjectMixin],

    data: function () {
        return {}
    },

    computed: {
        displayComponent : function(){
            if (this.isAmendment){
                return AmendmentTextDisplay
            }
            return ResolutionTextDisplay
        },

        modalId: function () {
            return `viewFullTextModal${this.motion.id}`;
        },

        ariaLabel: function () {
            return 'viewFullTextModalLabel' + this.motion.id;
        },

        title: function () {
            if (this.isAmendment) {
                return 'Proposed amendment'
            }
            return 'Resolution text'
        }
    },


}

</script>

<style scoped>

</style>
