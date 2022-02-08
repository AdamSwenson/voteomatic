<template>

<!--    <li class="list-group-item office-select-row"-->
<!--        v-bind:class="styling"-->
<!--    >{{officeName}}</li>-->

    <a href="#"
       class="list-group-item list-group-item-action"
       v-bind:class="styling"
    v-on:click="handleSelect">{{officeName}}</a>

</template>

<script>
import MotionMixin from "../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../mixins/meetingMixin";
import motionObjectMixin from "../../../mixins/motionObjectMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "office-select-row",

    /**
     * motion (the currently selected motion) will be available
     * via mixin. Hence this naming
     */
    props: ['motion'],


    mixins: [MeetingMixin, motionObjectMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        officeName: function(){
            return this.motion.content;
        },

        hasVoted: function(){

        },

        /**
         * Whether the motion that has been handed to this
         * component is the one globally selected.
         * @returns {boolean}
         */
        isSelected: function () {
            if (_.isUndefined(this.selectedMotion) || _.isNull(this.selectedMotion)) return false

            return this.motion.id === this.selectedMotion.id
        },

        //
        // isSelected: {get:function() {
        //         if (isReadyToRock(this.motion) && isReadyToRock(this.myMotion)) {
        //             window.console.log('rrtr', this.motion, this.myMotion);
        //             return this.motion === this.myMotion.id;
        //         }
        //         return false;
        //     }
        // },

        /**
         * The current globally active motion
         * @returns {any}
         */
        selectedMotion: function () {
            return this.$store.getters.getActiveMotion;
        },

        styling: {
            get: function () {
                if (this.isSelected) {
                    return ' bg-info '
                }

            },
            default: ''
        },

    },

    computed: {},

    methods: {
        handleSelect: function(){
            // window.console.log('setting office ');
            this.$store.dispatch('setOfficeForVoting', this.motion);
        }
    }

}
</script>

<style scoped>

</style>
