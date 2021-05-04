<template>

    <li class="list-group-item">
        <button v-bind:class="styling"
                v-on:click="setMeeting"
        >Select</button>
        {{ meeting.date }}    {{ meeting.name }}
    </li>
</template>

<script>

import MeetingMixin from '../../mixins/meetingMixin';
import ChairMixin from "../../mixins/chairMixin";


export default {
    name: "meeting-list-item",

    mixins : [ChairMixin],
    data: function () {
        return {}
    },


    props: ['meeting'],

    computed: {
        styling: function () {
            if (this.isSelected) return 'btn btn-primary'

            return 'btn btn-outline-primary'
        },


    },

    asyncComputed: {

        selectedMeeting: function () {
            return this.$store.getters.getActiveMeeting;
        },

        isSelected: function () {

            if (_.isUndefined(this.selectedMeeting) || _.isNull(this.selectedMeeting)) return false

            return this.meeting.id === this.selectedMeeting.id
        }


    },

    methods: {
        setMeeting: function () {
            this.$store.dispatch('setActiveMeeting', this.meeting);
            // this.$store.commit('setMeeting', this.meeting);
        }

    },
}
</script>

<style scoped>

</style>
