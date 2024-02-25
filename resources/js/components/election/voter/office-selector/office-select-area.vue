<template>
    <div class="card office-select-area" >
<!--    <div class="card office-select-area" style="width: 18rem;">-->
<!--        <h5 class="card-header">-->
<!--            Office-->
<!--        </h5>-->

        <div class="list-group list-group-flush">
            <instructions-row></instructions-row>
            <office-select-row
                v-if="showOffices"
                :motion="m"
                v-for="m in offices"
                :key="m.id"
            ></office-select-row>
           <proposition-select-row
               v-if="showPropositions"
               :motion="p"
               v-for="p in propositions"
               :key="p.id"
           ></proposition-select-row>
            <summary-select-row></summary-select-row>
        </div>
    </div>

        <!--        <ul class="list-group list-group-flush">-->
        <!--            <office-select-row :motion="m" v-for="m in motions" :key="m.id"></office-select-row>-->
        <!--        </ul>-->

        <!--        <div class="card-footer">-->
        <!--            <button class="btn btn-outline-info" v-on:click="handlePrevious">Previous</button>-->
        <!--            <button class="btn btn-outline-info" v-on:click="handleNext">Next</button>-->
        <!--        </div>-->


</template>

<script>
import MotionMixin from "../../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../../mixins/meetingMixin";
import motionObjectMixin from "../../../../mixins/motionObjectMixin";
import OfficeSelectRow from "./office-select-row";
import SummarySubmitCard from "../summary-submit-card";
import SummarySelectRow from "./summary-select-row";
import InstructionsRow from "./instructions-row";
import PropositionSelectRow from "./proposition-select-row";

export default {
    name: "office-select-area",
    components: {PropositionSelectRow, InstructionsRow, SummarySelectRow, SummarySubmitCard, OfficeSelectRow},
    props: {
        showOffices : {
            default : true
        },
        showPropositions : {
            default: true
        }
    },
    mixins: [MotionMixin, MeetingMixin, motionObjectMixin],


    data: function () {
        return {}
    },

    asyncComputed: {
        offices : function(){

            let m = this.$store.getters.getStoredMotions;
            if (_.isUndefined(m)) return [];

            m = _.filter(m, (o) => {
            return o.type !== 'proposition';
            });

            m = _.sortBy(m, ['id']);
            // m = _.reverse(m);

            return m;

        },

        propositions : function(){

            let m = this.$store.getters.getStoredMotions;
            if (_.isUndefined(m)) return [];

            m = _.filter(m, (o) => {
                return o.type === 'proposition';
            });

            m = _.sortBy(m, ['id']);
            // m = _.reverse(m);

            return m;

        },

        motions: function () {
            let m = this.$store.getters.getStoredMotions;
            if (_.isUndefined(m)) return [];


            m = _.sortBy(m, ['id']);
            // m = _.reverse(m);

            return m;
        },

    },

    computed: {},

    methods: {
        // handleNext : function(){
        //     this.$store.dispatch('nextOfficeInStack');
        // },
        // handlePrevious: function(){
        //     this.$store.dispatch('previousOffice');
        // }
    }

}
</script>

<style scoped>

</style>
