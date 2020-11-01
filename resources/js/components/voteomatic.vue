<template>

    <div class="voteomatic">
        <div class="card">
            <div class="card-title">
                <router-tabs></router-tabs>
            </div>

            <div class="card-body">
                <!--                <div class="content">-->

                <router-view name="main"></router-view>

                <!--                </div>-->
            </div>
        </div>

        <div class="card-footer">
            <div class="text-center">
                <refresh-button></refresh-button>
            </div>

        </div>

    </div>

</template>

<script>

//The main page for anything
import VotePage from "./main/vote-page";
import Motion from '../models/Motion';
import MeetingMixin from './storeMixins/meetingMixin';
import RouterTabs from "./navigation/router-tabs";
import RefreshButton from "./controls/refresh-button";

export default {
    name: "voteomatic",
    components: {RefreshButton, RouterTabs, VotePage},

    mixins: [MeetingMixin],

    data: function () {
        return {
            isReady: false,
            isSetup: false,
        }
    },

    computed: {


        //     //the motion being voted upon
        //     motion: function () {
        //         //todo convert to
        //
        //         let d = window.startData.motion;
        //         let m = new Motion(d);
        //
        //         // let m = new Motion(d.id, d.content, d.description, d.requires);
        //         return m
        //     }
    },

    mounted: function () {
        let me = this;
        //parse data from page and store stuff
        this.$store.dispatch('initialize').then(function () {
            me.$router.push('meeting-home');
            window.console.log('voteomatic', 'isReady', 159, me.isReady);
        });

    }


}
</script>

<style scoped>

</style>
