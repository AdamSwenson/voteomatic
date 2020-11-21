<template>
    <button
        v-bind:class="styling"
        data-toggle="modal"
        data-target="#deleteMotionModal"
    >Delete Motion</button>


</template>

<script>

import MotionMixin from "../../../mixins/motionMixin";

export default {
    name: "delete-motion-button",

    props: [],

    mixins: [MotionMixin],

    data: function () {
        return {
            styling: "btn btn-danger "

        }
    },

    computed: {},
    methods: {
    //     handleClick: function () {
    //
    //         let me = this;
    //
    //         //First we create and store a new motion from the
    //         //provided template
    //         let p = this.$store.dispatch('deleteMotion', me.motion)
    //             .then(function () {
    //             });
    //     }
    }
}
</script>

<style scoped>

</style>
