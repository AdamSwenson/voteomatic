<template>
    <div class="summary-office-listing card-body"
         v-bind:class="styling">
        <h4>{{ officeName }}</h4>

        <div class="ms-3">
            <p class='h3' v-if="hasError">There was a problem with your selections for this office. Please fix it in order to record your votes</p>

            <p v-else-if="hasUnderSelectionWarning">You have selected less than the maximum allowed number of candidates for this office.
            That is allowed, but you will not be able to select additional candidates after recording your votes.</p>

            <ul>
                <li v-for="c in selectedCandidates"
                    :key="c.id"
                    v-bind:class="styling"
                ><span v-html="c.nameAndInfo"></span>
                </li>
            </ul>


        </div>

    </div>
</template>

<script>
import {isReadyToRock} from "../../../../utilities/readiness.utilities";

export default {
    name: "summary-office-listing",

    props: ['motion'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        officeName: function () {
            if (!isReadyToRock(this.motion)) return '';
            return this.motion.content;
        },

        selectedCandidates: function () {
            if (!isReadyToRock(this.motion)) return [];

            return this.$store.getters.getSelectedCandidatesForMotion(this.motion)
        },

        hasUnderSelectionWarning: function(){
            if (!isReadyToRock(this.motion)) return false;
return this.$store.getters.showUnderSelectionWarningForMotion(this.motion);
        },

        hasError: function () {
            if (!isReadyToRock(this.motion)) return false;

            return this.$store.getters.showOverSelectionWarningForMotion(this.motion);
        },

        styling: function () {
            if (!isReadyToRock(this.motion)) return '';
            if (this.hasError) return ' error '; ' text-danger ';
            if (this.hasUnderSelectionWarning) return ' warn ';
        },

        outlineStyling : function(){
            if (!isReadyToRock(this.motion)) return '';
            if (this.hasError) return ' border-danger ';
            if (this.hasUnderSelectionWarning) return ' border-warning ';

        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>
.warn{
color: #8a3c03;
}

.error {
    color: #a72323;
}

</style>
