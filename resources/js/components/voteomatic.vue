<template>

    <div class="voteomatic">
        <election-module v-if="isElection"></election-module>

        <public-pmode v-else-if="isPublicPmode"></public-pmode>

        <meeting-module v-else></meeting-module>

        <page-footer></page-footer>
    </div>

</template>

<script>


import ElectionModule from "./election-module";
import MeetingModule from "./meeting-module";
import PageFooter from "./navigation/page-footer";
import PmodeHome from "./rezzie-view/pmode-home";
import PublicPmode from "./public-pmode";

/**
 * This is the main page. All it does is decide which
 * mode we are in and display the current module.
 */
export default {
    name: "voteomatic",
    components: {
        PublicPmode,
        PmodeHome,
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

        isElection: function () {
            return window.startData.isElection;
        },

        isPublicPmode: function () {
            return window.isPublicPmode;
        }


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
