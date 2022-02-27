<template>
    <div class="summary-office-listing card-body"
         v-bind:class="styling">
        <h4>{{ officeName }}</h4>

        <div class="ml-3">
            <p v-if="hasError"
            >There was a problem with your selections for this office.</p>
            <ul>
                <li v-for="c in selectedCandidates"
                    :key="c.id"
                    v-bind:class="styling"
                >{{ c.nameAndInfo }}
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

        hasError: function () {
            if (!isReadyToRock(this.motion)) return false;

            return this.$store.getters.showOverSelectionWarningForMotion(this.motion);
        },

        styling: function () {
            if (!isReadyToRock(this.motion)) return '';
            if (this.hasError) return ' text-danger '
        },

        outlineStyling : function(){
            if (!isReadyToRock(this.motion)) return '';
            if (this.hasError) return ' border-danger '

        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
