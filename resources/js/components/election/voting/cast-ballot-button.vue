<template>

    <button
        class="btn btn-lg "
        v-bind:class="styling"
        v-on:click="handleClick"
        v-bind:aria-disabled="ariaDisabled"
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

        showOverSelectionWarning: function () {
            return this.$store.getters.showOverSelectionWarningForActiveMotion;
        },

        ariaDisabled: function () {
            if (this.showOverSelectionWarning) return true
        },

        styling: function () {
            if (this.showOverSelectionWarning) return 'btn-success disabled'
            return "btn-success ";
        }
    },

    computed: {},

    methods: {
        handleClick: function () {
            window.console.log('Record Vote button clicked');
            let me = this;
            if (!this.showOverSelectionWarning) {
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


}
</script>

<style scoped>

</style>
