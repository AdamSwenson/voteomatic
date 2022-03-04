<template>

    <button type="button"
            class="btn btn-success btn-lg btn-block"
            data-bs-toggle="modal"
            data-bs-target="#yayConfirmationModal"
            v-on:click="handleClick"
    >Aye</button>

</template>

<script>
export default {
    name: "yay-button",

    methods: {
        handleClick: function () {
            // this.$emit('yay-clicked');
        }
    }
}
</script>

<style scoped>

</style>
