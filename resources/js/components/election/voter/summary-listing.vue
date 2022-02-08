<template>
<div class="summary-listing card-body">
<h4>{{officeName}}</h4>

    <div class="ml-3">
        <ul>
            <li v-for="c in selectedCandidates" :key="c.id">{{c.nameAndInfo}}</li>
        </ul>


    </div>

</div>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "summary-listing",

    props: ['motion'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        officeName: function(){
            if(!isReadyToRock(this.motion)) return '';
            return this.motion.content;
        },

        selectedCandidates: function(){
            if(!isReadyToRock(this.motion)) return [];

            return this.$store.getters.getSelectedCandidatesForMotion(this.motion)
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
