<template>
    <div class="btn-group"
         role="group"
         aria-label="Event type controls for selecting meeting or election"
    >

        <input type="radio"
               class="btn-check"
               name="meetingSelect"
               id="meetingSelect"
               autocomplete="off"
               v-bind:checked="meetingCheckedState"
               v-on:click="setMeeting"
        >
        <label
            v-bind:class="meetingLabelStyle"
            for="meetingSelect"
        >Meeting</label>


        <input type="radio"
               class="btn-check"
               name="electionSelect"
               id="electionSelect"
               autocomplete="off"
               v-bind:checked="electionCheckedState"
               v-on:click="setElection"
        >
        <label
            v-bind:class="electionLabelStyle"
        >Election</label>

    </div>


    <!--    <div class="btn-group btn-group-toggle" data-bs-toggle="buttons">-->
    <!--        <label v-bind:class="meetingLabelStyle">-->
    <!--            <input type="radio" name="options" id="meetingSelect"-->
    <!--                   v-bind:checked="meetingCheckedState"-->
    <!--                   v-on:click="setMeeting"-->
    <!--            > Meeting-->
    <!--        </label>-->

    <!--        <label v-bind:class="electionLabelStyle">-->
    <!--            <input type="radio" name="options" id="electionSelect"-->
    <!--                   v-bind:checked="electionCheckedState"-->
    <!--                   v-on:click="setElection"> Election-->
    <!--        </label>-->
    <!--    </div>-->

    <!--        <div class="custom-control custom-switch">-->
    <!--        <input type="checkbox"-->
    <!--               class="custom-control-input"-->
    <!--               v-bind:id="switchId">-->
    <!--        <label class="custom-control-label" v-bind:for="switchId"></label>-->
    <!--    </div>-->
</template>

<script>
import ModeMixin from "../../mixins/modeMixin";

export default {
    name: "mode-toggle-switch",

    props: [],


    mixins: [ModeMixin],
    data: function () {
        return {
            switchId: 'mode-toggle',
            inactiveStyle: "btn btn-outline-primary ",
            activeStyle: "btn btn-primary active"
        }
    },

    asyncComputed: {
        meetingLabelStyle: function () {
            if (this.isMeeting) return this.activeStyle
            return this.inactiveStyle;
        },

        meetingCheckedState: function () {
            if (this.isMeeting) return "checked";
            return ""
        },


        electionLabelStyle: function () {
            if (this.isElection) return this.activeStyle;
            return this.inactiveStyle;
        },

        electionCheckedState: function () {
            if (this.isElection) return "checked";
            return ""
        }


    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
