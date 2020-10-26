<template>

    <button type="button" class="btn btn-success btn-lg"
            v-on:click="handleClick"
    >Yay
    </button>
</template>

<script>
export default {
    name: "yay-button",

    methods: {
        handleClick: function () {
            this.$emit('yay-clicked');
        }
    }
}
</script>

<style scoped>

</style>
