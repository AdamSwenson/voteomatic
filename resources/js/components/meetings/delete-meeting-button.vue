<template>
    <button
        v-bind:class="styling"
        data-toggle="modal"
        data-target="#deleteMeetingModal"
    >Delete Meeting</button>


</template>

<script>

import MeetingMixin from '../../mixins/meetingMixin'

export default {
    name: "delete-meeting-button",

    props: [],
    mixins: [MeetingMixin],


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
    //         //First we create and store a new meeting from the
    //         //provided template
    //         let p = this.$store.dispatch('deleteMeeting', me.meeting)
    //             .then(function () {
    //             });
    //     }
    }
}
</script>

<style scoped>

</style>
