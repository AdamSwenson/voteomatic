<template>
    <!--Can't use a button because vue is a pain with attributes like disabled-->
    <!--    VOT-288/301: Switched back to button because the link was
    causing the tab to close. The disabled attribute seems to work now with
    the new vue -->
    <!--    <a href="#"-->

    <button
        v-bind:class="styling"
        v-bind:tabindex="tabIndex"
        role="button"
        v-bind:aria-disabled="ariaDisabled"
        v-on:click="setMotion"
    >{{ buttonText }}
    </button>
    <!--    </a>-->

</template>

<script>

import MotionObjectMixin from '../../mixins/motionObjectMixin';
import NavigationMixin from '../../mixins/NavigationMixin';
import MeetingMixin from '../../mixins/meetingMixin';
import EndVotingButton from "./end-voting-button";
import Payload from "../../models/Payload";
import ChairMixin from "../../mixins/chairMixin";

export default {
    name: "motion-select-button",
    components: {EndVotingButton},
    mixins: [MeetingMixin, MotionObjectMixin, ChairMixin, NavigationMixin ],
    data: function () {
        return {
            classBase: 'btn btn-lg btn-block '
        }
    },

    props: ['motion'],


    computed: {
    // asyncComputed: {
        styling: function () {
            if (this.isDisabled) return this.classBase + ' btn-outline-primary disabled'
            if (this.isSelected) return this.classBase + ' btn-primary active';

            return this.classBase + ' btn-outline-primary';
            // return 'btn btn-outline-primary btn-lg  btn-block'
        },

        ariaDisabled: function(){
            if(this.isDisabled) return true;
            return false;
        },

        tabIndex: function(){
          if(this.isDisabled) return '-1';
          return 1;
        },

        isDisabled: function () {
            return this.isSuperseded;

        },
        buttonText: {
            get: function () {
                if (this.isSelected) return "Selected";

                return "Select"
            },
            default: 'Select'
        },


        selectedMotion: function () {
            return this.$store.getters.getActiveMotion;
        },

        isSelected: function () {

            if (_.isUndefined(this.selectedMotion) || _.isNull(this.selectedMotion)) return false

            return this.motion.id === this.selectedMotion.id
        }


    }
    ,

    methods: {
        setMotion: function () {
            if(this.isChair) {
                this.setMotionChair();
            }else{
                this.setMotionLocal()
            }
        },

        /**
         * Sets the motion as current on the server and pushes
         * to all users
         */
        setMotionChair : function(){
            let pl = Payload.factory({'motionId': this.motion.id, 'meetingId': this.meeting.id});
            this.$store.dispatch('setCurrentMotion', pl)
        },


        /**
         * Sets the motion as current on client but not
         * on server. Used by regular user to select past
         * motions and view results
         */
        setMotionLocal : function(){
            this.$store.commit('setMotion', this.motion);
            // this.$store.dispatch('forceNavigationToHome');
            // window.console.log('motion-select-button', 'setMotionLocal', 115,);
        }


    }
    ,
}
</script>

<style scoped>

</style>
