<template>

    <li class="list-group-item mb-2"
        v-bind:class="borderStyle">
        <button
            v-if="showButton"
            v-bind:class="styling"
            v-on:click="setMeeting"
        >{{ buttonText }}
        </button>
        {{ meeting.date }} {{ meeting.name }}
    </li>
</template>

<script>

import MeetingMixin from '../../mixins/meetingMixin';
import ChairMixin from "../../mixins/chairMixin";


export default {
    name: "meeting-list-item",

    mixins: [ChairMixin],
    data: function () {
        return {}
    },


    props: ['meeting'],

    computed: {
        styling: function () {
            if (this.meeting.type === 'election' && this.meeting.phase === 'voting') return 'btn btn-success';


            if (this.isSelected) return 'btn btn-primary'

            return 'btn btn-outline-primary'
        },

    }
    ,

    asyncComputed: {

        selectedMeeting: function () {
            return this.$store.getters.getActiveMeeting;
        }
        ,

        isSelected: function () {

            if (_.isUndefined(this.selectedMeeting) || _.isNull(this.selectedMeeting)) return false

            return this.meeting.id === this.selectedMeeting.id
        },


        buttonText: function () {
            if (this.meeting.type !== 'election') return 'Select';

            switch (this.meeting.phase) {
                case 'voting':
                    return 'Vote';
                    break;
                case 'results':
                    return 'Results';
                    break;
                default:
                    return 'Select';

            }
        },

        showButton: function () {
            if(window.isAdmin === '1') return true;

            if (this.meeting.type !== 'election') return true;

            switch (this.meeting.phase) {
                case 'voting':
                    return true
                    break;
                case 'results':
                    return true;
                    break;
                default:
                    return false;

            }
        },
        borderStyle: function () {
            if (this.meeting.type !== 'election') return '';

            if (this.meeting.phase === 'voting') return "border border-success"

            if (this.meeting.phase === 'results') return "border border-primary"
        }

    },

    methods: {
        setMeeting: function () {

            this.$store.dispatch('setActiveMeeting', this.meeting);
            // this.$store.commit('setMeeting', this.meeting);
        }

    }
    ,
}
</script>

<style scoped>

</style>
