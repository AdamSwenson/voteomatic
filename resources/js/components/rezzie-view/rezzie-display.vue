<template>
    <div
        v-bind:id="itemId"
        class="rezzie-display accordion-item"
    >
        <h2 class="accordion-header"
            v-bind:id="headingId">
            <button class="accordion-button "
                    v-bind:class="buttonStyling"
                    type="button"
                    data-bs-toggle="collapse"
                    v-bind:aria-expanded="isOpen"
                    v-bind:aria-controls="bodyId"
                    v-bind:data-bs-target="bodyTarget"
                    v-on:click="handleClick"
            >
                {{ headerText }}
            </button>
        </h2>

        <div v-bind:id="itemId"
             v-bind:class="styling"
             v-bind:aria-labelledby="headingId"
             v-bind:data-bs-parent="parentTarget"
        >

            <div class="accordion-body">


                <div class="body-text">
                    <!--                    v-if="motion.is_resolution">-->
                    <compiled-rezzie-text :html="amendmentText"></compiled-rezzie-text>
                    <!--                    <resolution-amendment-text-display-->
                    <!--                        v-if="isResolution"-->
                    <!--                        :original-text="originalText"-->
                    <!--                        :amendment-text="amendmentText"-->
                    <!--                    ></resolution-amendment-text-display>-->

                </div>

<!--                <div class="body-text" v-else v-html="motion.content"></div>-->


                <p-mode-chair-controls
                    v-if="isChair"
                    :motion="motion"
                ></p-mode-chair-controls>

            </div>

        </div>
    </div>

</template>

<script>
import MeetingMixin from "../../mixins/meetingMixin";
import motionObjectMixin from "../../mixins/motionObjectMixin";
import ChairMixin from "../../mixins/chairMixin";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import PModeChairControls from "./p-mode-chair-controls";
import AmendmentMixin from "../../mixins/amendmentMixin";
import ResolutionAmendmentTextDisplay from "../motions/text-display/resolution-amendment-text-display";
import CompiledRezzieText from "./compiled-rezzie-text";


window.bootstrap = require('bootstrap');

export default {
    name: "rezzie-display",

    components: {CompiledRezzieText, ResolutionAmendmentTextDisplay, PModeChairControls},

    props: ['motion', 'parentId'],

    mixins: [AmendmentMixin, ChairMixin, MeetingMixin, motionObjectMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        amendments: function () {
        },

        buttonStyling: function () {
            if (!this.isOpen) return ' collapsed '
        },
        styling: function () {
            let s = 'accordion-collapse collapse ';
            if (this.isOpen) {
                s += ' show '
            }
            return s;
        },

        isOpen: function () {
            if (!isReadyToRock(this.motion)) return false;
            //can't use motionMixin because will collide on name motion
            let m = this.$store.getters.getActiveMotion;
            if (!isReadyToRock(m)) return false

            return m.id === this.motion.id;
        },

        /** The full thing displayed. Includes
         * text, id number, and status if complete
         */
        headerText: function () {
            if (!isReadyToRock(this.motion, 'title')) return ''
//dev
            return this.motion.title;

        },

        /** Name of the resolution excluding any id number
         *
         */
        title: function () {
        },

        /**
         * Id number/string of the resolution
         */
        identifier: function () {
        },


    },

    computed: {
        /**
         * id of body
         * @returns {string}
         */
        bodyId: function () {
            return this.itemId + 'body';
        },

        /**
         * id of body
         * @returns {string}
         */
        bodyTarget: function () {
            return '#' + this.itemId + 'body';
        },


        /**
         * Id of outermost div of this item
         * @returns {string}
         */
        itemId: function () {
            if (isReadyToRock(this.motion)) return 'rezzieDisplay' + this.motion.id;
            return ''
        },

        /**
         * itemId with # prepended
         * @returns {string}
         */
        itemTarget: function () {
            return '#' + this.itemId;
        },

        /**
         * id of header
         * @returns {string}
         */
        headingId: function () {
            return this.itemId + 'heading';
        },

        parentTarget: function () {
            return '#' + this.parentId;
        }


    },

    methods: {

        handleClick: function () {
            //Parliamentarian mode shouldn't be setting for everyone,
            //so use the commit directly
            this.$store.commit('setMotion', this.motion);

        }
    },

    mounted() {
        // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        // var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        //     return new bootstrap.Tooltip(tooltipTriggerEl)
        // })

        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        })

    }

}
</script>

<style scoped>

</style>
