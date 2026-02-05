<template>

    <!-- Button trigger modal -->
        <button type="button"
                v-bind:class="styling"
                data-bs-toggle="modal"
                v-bind:data-bs-target="modalId"
        >View full text</button>

</template>

<script>

export default {
    name: "view-full-text-button",
    props: ['motion'],

    computed: {
        modalId: function() {
            return `#viewFullTextModal${this.motion.id}`;
        },

        styling: function () {
           return "btn btn-primary btn-lg text-light ";
        },


    },

}
</script>

<style scoped>

</style>
