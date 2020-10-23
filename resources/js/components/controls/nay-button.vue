<template>
    <button type="button" class="btn btn-danger"
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
            this.$emit('nay-clicked');
        }
    }
}
</script>

<style scoped>

</style>
