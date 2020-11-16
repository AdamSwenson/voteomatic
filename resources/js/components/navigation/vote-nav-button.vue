<template>

    <button
        v-bind:class="styling"
        v-on:click="handleClick"
        >Vote</button>

</template>

<script>
export default {
    name: "vote-nav-button",

    props: ['motion'],

    mixins: [],

    data: function () {
        return {
            baseStyle: "btn btn-lg btn-block "
        }
    },

    computed: {},

    asyncComputed: {
        hasVotedOnCurrentMotion: function () {
            return this.$store.getters.hasVotedOnCurrentMotion;
        },


        styling: {
            get: function () {
                if (! this.hasVotedOnCurrentMotion) {
                    return this.baseStyle + '  btn-warning '
                }

                return this.baseStyle + ' btn-outline-warning ';

            },
            default: '' //this.baseStyle
        },
    },

    methods: {
        handleClick: function () {
            this.$router.push('vote');
        }

    }
}
</script>

<style scoped>

</style>
