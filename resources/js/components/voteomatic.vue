<template>

    <div class="voteomatic">
        <router-tabs></router-tabs>


        <div class="card">
            <div class="card-content">
                <div class="content">

                    <router-view name="main"></router-view>

                </div>
            </div>
        </div>

        <!--            <vote-page v-if="!isSetup"-->
        <!--                   :motion="motion"-->
        <!--        ></vote-page>-->
    </div>

</template>

<script>

//The main page for anything
import VotePage from "./vote-page";
import Motion from '../models/Motion';
import MeetingMixin from './storeMixins/meetingMixin';
import RouterTabs from "./navigation/router-tabs";

export default {
    name: "voteomatic",
    components: {RouterTabs, VotePage},

    mixins: [MeetingMixin],

    data: function () {
        return {
            isReady: false,
            isSetup: false,
        }
    },

    computed: {
        //the motion being voted upon
        motion: function () {
            let d = window.startData.motion;
            let m = new Motion(d);

            // let m = new Motion(d.id, d.content, d.description, d.requires);
            return m
        }
    },

    mounted: function () {
        let me = this;
        //parse data from page and store stuff
        this.$store.dispatch('initialize').then(function () {

            window.console.log('waggleback', 'isReady', 159, me.isReady);
        });

    }


}
</script>

<style scoped>

</style>
