<template>
    <button type="button"
            class="btn btn-danger btn-lg btn-block"
            data-toggle="modal"
            data-target="#nayConfirmationModal"
            v-on:click="handleClick"
    >Nay
    </button>


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

</style>
