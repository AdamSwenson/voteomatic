<template>

    <button
        class="btn"
        v-bind:class="styling"
        v-on:click="handleClick"
    >Record selections
    </button>

</template>

<script>
export default {
    name: "cast-ballot-button",

    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {

        styling: function () {
            return "btn-warning";

            //
            // if(this.selected) return "btn-warning";
            //
            // return "btn-outline-warning";
        }
    },

    computed: {},

    methods: {
        handleClick: function () {
            window.console.log('Record Vote button clicked');
            let me = this;

            this.$store.dispatch('castElectionVote')
                .then(() => {
                    me.$store.dispatch('nextOffice')
                        .then(() => {
                    })
                        .catch(() => {
                            //If there are no further offices to vote
                            //upon, it will reject.
                            me.$emit('election-complete');
                            window.console.log('election complete');
                    });
                });

        }
    }


}
</script>

<style scoped>

</style>
