<template>

    <div class="voteomatic">
        <election-module v-if="isElection"></election-module>

        <meeting-module v-else></meeting-module>

        <page-footer></page-footer>
    </div>

</template>

<script>


import ElectionModule from "./election-module";
import MeetingModule from "./meeting-module";
import PageFooter from "./navigation/page-footer";

/**
 * This is the main page. All it does is decide which
 * mode we are in and display the current module.
 */
export default {
    name: "voteomatic",
    components: {
        PageFooter,
        MeetingModule,
        ElectionModule,
       },

    data: function () {
        return {
            isReady: false,
            isSetup: false,
        }
    },

    computed: {

        isElection: function(){
            return window.startData.isElection;
        },



    },

    mounted: function () {


    }


}
</script>

<style scoped>
.refresh-area {
    margin-top: 2em;
}
</style>
