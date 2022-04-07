<template>
    <div class="d-grid gap-2">
    <button type="button"
            class="btn btn-danger "
            data-bs-toggle="modal"
            data-bs-target="#nayConfirmationModal"
            v-on:click="handleClick"
    ><span class="nayText">Nay</span>
    </button>

    </div>
</template>

<script>
export default {
    name: "nay-button",

    data: function () {
        return {
            label: 'Nay'
        }
    },

    methods: {
        handleClick: function () {
            // this.$emit('nay-clicked');
        }
    }
}
</script>

<style scoped>
.nayText{
    font-weight: bold;
}
</style>
