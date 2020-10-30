<template>

    <li class="list-group-item">
        <button v-bind:class="styling"
                v-on:click="setMotion"
        >Select
        </button>
        {{ motion.content }}
    </li>
</template>

<script>

import MotionMixin from '../storeMixins/motionMixin';

export default {
    name: "motion-select-button",

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

    asyncComputed : {

        selectedMotion : function (){
            return  this.$store.getters.getActiveMotion;
        },

        isSelected: function () {

            if (_.isUndefined(this.selectedMotion) || _.isNull(this.selectedMotion)) return false

            return this.motion.id === this.selectedMotion.id
        }


    },

    methods: {
        setMotion: function () {
            this.$store.commit('setMotion', this.motion);
        }

    },
}
</script>

<style scoped>

</style>
