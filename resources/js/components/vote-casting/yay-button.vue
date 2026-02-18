<template>
    <div class="d-grid gap-2">
    <button type="button"
            class="btn btn-success fs-2"
            data-bs-toggle="modal"
            data-bs-target="#yayConfirmationModal"
            v-on:click="handleClick"
    >Yay</button>
    </div>
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
