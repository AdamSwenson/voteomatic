<template>

    <button
        class="btn"
        v-bind:class="styling"
        v-on:click="handleClick"
    >Select
    </button>
</template>

<script>
export default {
    name: "candidate-button",

    props: ['candidate'],

    mixins: [],

    data: function () {
        return {


        }
    },

    asyncComputed: {
        selected : function(){
            return false;
        },

        styling: function () {
            if(this.selected) return "btn-info";

            return "btn-outline-info";
        }
    },

    computed: {

    },

    methods: {

        handleClick: function () {
            window.console.log('Vote button clicked for ', this.candidate.name);
let me = this;
let pl = {
    motionId: this.candidate.motion_id,
    candidateId : this.candidate.id
};
            this.$store.dispatch('castElectionVote', pl)
                .then((response) => {
                window.console.log(response.data);
            });

        }
    }

}
</script>

<style scoped>

</style>
