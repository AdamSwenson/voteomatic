<template>
    <button
        v-bind:class="styling"
        data-toggle="modal"
        data-target="#deleteMeetingModal"
    >Delete {{type}}</button>


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

    computed: {
        // type : function(){
        //     return this.meeting.type
        // },
        //
        // typeCapitalized : function(){
        //     return this.type.toUpperCase();
        // },
        //

    },
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
