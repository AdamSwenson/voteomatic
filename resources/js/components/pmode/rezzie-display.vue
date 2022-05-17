<template>
    <div
        v-bind:id="itemId"
        class="rezzie-display accordion-item"
    >
        <h1 class="accordion-header"
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
                <motion-status-badge :is-passed="isPassed"
                ></motion-status-badge>&nbsp;&nbsp;{{ headerText }}&nbsp;&nbsp;&nbsp;&nbsp;<span
                v-if='isCurrent'
                class="badge bg-primary">Current</span>
            </button>
        </h1>

        <div v-bind:id="itemId"
             v-bind:class="styling"
             v-bind:aria-labelledby="headingId"
             v-bind:data-bs-parent="parentTarget"
        >

            <div class="accordion-body">

                <div class="body-text">
                    <div class="row">
                        <div class="col">
                            <!--                    v-if="motion.is_resolution">-->
                            <compiled-rezzie-text :html="amendmentText"
                            ></compiled-rezzie-text>

                        </div>

                        <!--                dev reenable check after VOT-190 working?-->
                        <!--                <div class="body-text" v-else v-html="motion.content"></div>-->
                    </div>
                </div>


                        <p-mode-chair-controls
                            v-if="isChair"
                            :motion="motion"
                        ></p-mode-chair-controls>
<!--                    </div>-->
<!--                </div>-->

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
            isReady: false,
        }
    },

    watch: {
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

        /**
         * Id of the motion whose accordion is open
         */
        openMotionId: function () {
            return this.$store.getters.getOpenMotionId;
        },

        currentMotion: function () {
            return this.$store.getters.getActiveMotion;
        },

        /**
         * Whether this is the motion at the top of the stack
         */
        isCurrent: function () {
            if (!isReadyToRock(this.motion)) return false;
            //can't use motionMixin because will collide on name motion
            // let m = this.$store.getters.getActiveMotion;
            if (!isReadyToRock(this.currentMotion)) return false
            return this.currentMotion.id === this.motion.id;
        },

        /**
         * Whether this motion's accordion should be open
         */
        isOpen: function () {
            if (!isReadyToRock(this.motion)) return false;
            //can't use motionMixin because will collide on name motion
            // let m = this.$store.getters.getActiveMotion;
            if (!isReadyToRock(this.openMotionId)) return false

            return this.openMotionId === this.motion.id;
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
            // this.$store.commit('setMotion', this.motion);

            //Using a separate store of which motion accordion is
            //open so that won't lose which motion is currently pending for everyone
            this.$store.commit('setOpenMotion', this.motion);

            this.initializePopovers();
        },


        initializePopovers() {
            // this.$nextTick(function () {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            });
            window.console.log('popovers initialized');

            // window.console.log('popovers', popoverTriggerList,);
            // });
        }
    },

    mounted() {
        let me = this;
        this.$nextTick(function () {
            me.$store.dispatch('setOpenMotionToCurrent');

            //dev this is incredibly stupid, but it works as a kludge for VOT-194
            setTimeout(() => {
                // window.console.log('time');
                me.initializePopovers();
            }, 6000)

        });
    }

}
</script>

<style scoped>

</style>
