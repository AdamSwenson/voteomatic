<template>
    <button class="btn btn-outline-primary"
            v-on:click="handleClick"
    >Create main motion
    </button>

</template>

<script>
import MeetingMixin from "../../mixins/meetingMixin";

export default {
    name: "create-motion-button",

    props: [],

    mixins: [MeetingMixin],

    data: function () {
        return {}
    },

    computed: {},

    methods: {
        initializeMotion: function () {

            let p = this.$store.dispatch('createMotion', this.meeting.id);
            let me = this;
            p.then(() => {
                me.$emit('create-motion-clicked');
            });
        },


        handleClick: function () {
            this.initializeMotion();

        }
    }
}
</script>

<style scoped>

</style>
