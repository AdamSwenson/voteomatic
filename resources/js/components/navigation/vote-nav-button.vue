<template>

    <div class="d-grid gap-2">
    <button
        v-bind:class="styling"
        v-on:click="handleClick"
        tabindex="0"
        >Vote</button>
    </div>

</template>

<script>
import navigationButtonMixin from "../../mixins/navigationButtonMixin";

export default {
    name: "vote-nav-button",

    props: ['motion'],

    mixins: [navigationButtonMixin],

    data: function () {
        return {
            baseStyle: "btn btn-lg  "
        }
    },

    computed: {

        hasVotedOnCurrentMotion: function () {
            return this.$store.getters.hasVotedOnMotion(this.motion);
            // return this.$store.getters.hasVotedOnCurrentMotion;
        },


        styling: {
            get: function () {
                if ( this.hasVotedOnCurrentMotion) {
                    // return this.baseStyle + '  btn-outline-success '

                    return this.baseStyle + '  btn-success '

                }

                return this.baseStyle + '  btn-success '

                //This got too light after removed the background
                // return this.baseStyle + ' btn-outline-warning ';

            },
            default: '' //this.baseStyle
        },
    },

    methods: {
        handleClick: function () {
            this.setMotionLocal();
            this.$router.push('vote');
        }

    }
}
</script>

<style scoped>

</style>
