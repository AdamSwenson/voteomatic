<template>

    <button v-bind:class="styling"
            v-on:click="setMotion"
    >{{buttonText}}</button>

</template>

<script>

import MeetingMixin from '../../mixins/meetingMixin';
import EndVotingButton from "./end-voting-button";
import Payload from "../../models/Payload";

export default {
    name: "motion-select-button",
    components: {EndVotingButton},
    mixins : [MeetingMixin],
    data: function () {
        return {}
    },

    props: ['motion'],

    computed: {
        styling: function () {
            if (this.isSelected) return 'btn btn-primary btn-lg btn-block'

            return 'btn btn-outline-primary btn-lg  btn-block'
        },


    },

    asyncComputed: {
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
            let pl = Payload.factory({'motionId' : this.motion.id, 'meetingId' : this.meeting.id});
            this.$store.dispatch('setCurrentMotion', pl)
            // this.$store.commit('setMotion', this.motion);
        },


    }
    ,
}
</script>

<style scoped>

</style>
