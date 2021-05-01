<template>
    <div class="meeting-display card">

<!--        <div class="card-header">-->
<!--            <h4 class="card-title">{{}}information</h4>-->
<!--        </div>-->

        <div class="card-body meeting-display">

            <h5 class="card-title text-left">{{ meetingName }}</h5>

            <h5 class="card-subtitle text-left">{{ meetingDate }}</h5>

            <div class="meeting-link text-left mt-4" v-if="isChair">
                <h5 class="card-subtitle user-select-all">{{ meetingLink }}</h5>
                <p class="text-muted ">Use this link in creating the Canvas assignment.</p>

                <p>NB, this page doesn't do anything useful unless
                    you have the the voteomatic configured as an app in your own canvas class.</p>

            </div>

            <div class="meeting-specific" v-if="isMeeting">
                <p class="text-secondary">[ Optional short description of meeting will be here ]</p>

                <p class="text-secondary">[ Optional agenda will be here ]</p>

            </div>

        </div>

<!--        <div class="card-footer">-->
<!--        </div>-->

    </div>


</template>

<script>
import MeetingMixin from '../../mixins/meetingMixin';
import ChairMixin from "../../mixins/chairMixin";
import ModeMixin from "../../mixins/modeMixin";

// import Payload from "../../models/Payload";

export default {
    name: "event-display-card",

    props: [],

    mixins: [MeetingMixin, ChairMixin, ModeMixin],

    data: function () {
        return {
            // linkBase: "https://voteomatic.com/lti-entry/"
        }
    },

    asyncComputed: {

    },


    computed: {},
    methods: {}

}
</script>

<style scoped>

</style>
