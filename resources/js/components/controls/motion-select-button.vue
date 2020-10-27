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
    // mixins : [MotionMixin],
    data: function () {
        return {}
    },

    props: ['motion'],

    computed: {
        styling: function () {
            if (this.isSelected) return 'btn btn-primary'

            return 'btn btn-outline-primary'
        },

        isSelected: function () {
            let selected = this.$store.getters.getMotion;

            if(_.isUndefined(selected) || _.isNull(selected)) return false

            return this.motion.id === selected.id
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
