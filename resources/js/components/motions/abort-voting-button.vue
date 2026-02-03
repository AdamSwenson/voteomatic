<template>

    <!-- Button trigger modal -->
    <div class="d-grid gap-2">
        <button type="button"
                v-bind:class="styling"
                data-bs-toggle="modal"
                data-bs-target="#abortVotingModal">
            Abort voting
        </button>
    </div>


</template>

<script>

export default {
    name: "abort-voting-button",
    props: ['motion'],

    computed: {

        styling: function () {
            let style = "btn btn-primary btn-lg  ";
            if (_.isUndefined(this.motion) || _.isNull(this.motion)) return style;

            if (this.isComplete) {
                style = 'btn btn-danger btn-lg disabled';
            }
            return style;
        },

        isComplete: function () {
            if (!_.isUndefined(this.motion) || !_.isNull(this.motion)) {
                return this.motion.isComplete;
            }

        }
    },


    methods: {
        endVoting: function () {
            this.$store.dispatch('abortVotingOnMotion', this.motion);
        }

    }
}
</script>

<style scoped>

</style>
