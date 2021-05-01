<template>

    <div class="setup-page">
        <meeting-setup></meeting-setup>

        <motion-setup :meeting="meeting"></motion-setup>

    </div>




</template>

<script>
import MeetingSetup from "./meetings/meeting-setup-card";
import MotionSetup from "./main/chair/motion-setup";
import Meeting from "../models/Meeting";

export default {
    name: "setup-page",
    components: {MotionSetup, MeetingSetup},
    // props : ['meeting'],

    data : function (){
        return {
            _motion : null,

            //todo dev
            meeting : new Meeting()
        }
    },


    computed : {

    },

    methods : {

    }

}
</script>

<style scoped>

</style>
