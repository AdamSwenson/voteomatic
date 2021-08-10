<!--<template>-->
<!--    <button class="btn btn-outline-primary"-->
<!--            v-on:click="handleClick"-->
<!--    >Create main motion-->
<!--    </button>-->

<!--</template>-->

<script>
import MeetingMixin from "../../mixins/meetingMixin";
import ButtonParent from "../parents/button-parent";

export default {
    name: "create-motion-button",

    extends : ButtonParent,

    props: [],

    mixins: [MeetingMixin],

    data: function () {
        return {
            label : "Create main motion",
            styling : "  btn-outline-primary "

        }
    },

    computed: {},

    methods: {
        initializeMotion: function () {

            let p = this.$store.dispatch('initializeDraftMainMotion');
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
