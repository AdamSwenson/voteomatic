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
            ><motion-status-badge :is-passed="isPassed"
            ></motion-status-badge> {{ headerText }}
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
                    <compiled-rezzie-text :html="amendmentText"
                    ></compiled-rezzie-text>
                    <!--                    <resolution-amendment-text-display-->
                    <!--                        v-if="isResolution"-->
                    <!--                        :original-text="originalText"-->
                    <!--                        :amendment-text="amendmentText"-->
                    <!--                    ></resolution-amendment-text-display>-->

                </div>

                <!--                dev reenable check after VOT-190 working?-->
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
import MotionResultsMixin from "../../mixins/motionResultsMixin";
import MotionStatusBadge from "../motions/badges/motion-status-badge";


window.bootstrap = require('bootstrap');

export default {
    name: "rezzie-display",

    components: {MotionStatusBadge, CompiledRezzieText, ResolutionAmendmentTextDisplay, PModeChairControls},

    /**
     * motion should be the root motion of the group
     */
    props: ['motion', 'parentId'],

    mixins: [ChairMixin, MeetingMixin, motionObjectMixin,
        MotionResultsMixin
    ],

    data: function () {
        return {
            isReady : false
        }
    },

    watch : {
        // isReady: function(){
        // // isOpen : function(){
        //     let me = this;
        //
        //     // if(this.isOpen === true){
        //         this.$nextTick(function () {
        //         window.console.log('isOpen changed');
        //         this.initializePopovers();
        //
        //     });
        //     // }
        //     }
    },

    asyncComputed: {
        amendmentText: function () {
            if (isReadyToRock(this.motion)) return this.motion.formattedContent;
            return '';
        },

        showText: function () {
            return isReadyToRock(this.amendmentText);
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
            let txt = '';
            if (isReadyToRock(this.identifier)) txt += `(${this.identifier}) `;
            txt += this.title;
            return txt;
//             if (!isReadyToRock(this.motion, 'title')) return ''
// //dev
//             return this.motion.title;

        },

        /** Name of the resolution excluding any id number
         *
         */
        title: function () {
            if (!isReadyToRock(this.motion, 'title')) return ''
            return this.motion.title;
        },

        /**
         * Id number/string of the resolution
         */
        identifier: function () {
            if (isReadyToRock(this.motion, 'resolutionIdentifier')) return this.motion.resolutionIdentifier;

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
            this.initializePopovers();
        },


        initializePopovers() {
            // this.$nextTick(function () {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            });
            // window.console.log('popovers', popoverTriggerList,);
            // });
        }
    },

    mounted() {
        let me = this;
        this.$nextTick(function () {
            // me.isReady = true;
            // window.console.log('mount');

            //dev this is incredibly stupid, but it works as a kludge for VOT-194
            setTimeout(() => {
                // window.console.log('time');
                me.initializePopovers();
            }, 500)

});
    }

}
</script>

<style scoped>

</style>
