<template>

    <div class="voteomatic">
        <vote-page v-if="!isSetup"
                   :motion="motion"
        ></vote-page>
    </div>

</template>

<script>

//The main page for anything
import VotePage from "./vote-page";
import Motion from '../models/Motion';

export default {
    name: "voteomatic",
    components: {VotePage},
    data: function () {
        return {
            isSetup: false,
        }
    },

    computed: {
        //the motion being voted upon
        motion: function () {
            let d = window.startData.motion;
            let m = new Motion(d.id, d.content, d.description, d.requires);
            return m
        }
    }


}
</script>

<style scoped>

</style>
