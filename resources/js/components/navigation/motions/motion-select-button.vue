<template>

    <button v-bind:class="styling"
            v-on:click="setMotion"
    >{{buttonText}}</button>

</template>

<script>

import MotionMixin from '../../storeMixins/motionMixin';
import EndVotingButton from "./end-voting-button";

export default {
    name: "motion-select-button",
    components: {EndVotingButton},
    data: function () {
        return {}
    },

    props: ['motion'],

    computed: {
        styling: function () {
            if (this.isSelected) return 'btn btn-primary'

            return 'btn btn-outline-primary'
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
            this.$store.commit('setMotion', this.motion);
        }
        ,


    }
    ,
}
</script>

<style scoped>

</style>
