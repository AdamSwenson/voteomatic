<template>
    <!--Can't use a button because vue is a pain with attributes like disabled-->
    <a href="#"
       v-bind:class="styling"
       v-bind:tabindex="tabIndex"
       role="button"
       v-bind:aria-disabled="ariaDisabled"
       v-on:click="setMotion"
    >{{ buttonText }}</a>
    <!--    <button-->
    <!--        v-bind:class="styling"-->
    <!--            v-on:click="setMotion"-->
    <!--    >{{buttonText}}</button>-->

</template>

<script>

import MotionObjectMixin from '../../mixins/motionObjectMixin';
import MeetingMixin from '../../mixins/meetingMixin';
import EndVotingButton from "./end-voting-button";
import Payload from "../../models/Payload";

export default {
    name: "motion-select-button",
    components: {EndVotingButton},
    mixins: [MeetingMixin, MotionObjectMixin],
    data: function () {
        return {
            classBase: 'btn btn-lg btn-block '
        }
    },

    props: ['motion'],

    computed: {


    },

    asyncComputed: {
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
            let pl = Payload.factory({'motionId': this.motion.id, 'meetingId': this.meeting.id});
            this.$store.dispatch('setCurrentMotion', pl)
            // this.$store.commit('setMotion', this.motion);
        },


    }
    ,
}
</script>

<style scoped>

</style>
