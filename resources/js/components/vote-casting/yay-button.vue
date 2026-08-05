<template>
    <div class="d-grid gap-2">
    <button type="button"
            class="btn btn-success vote-choice"
            data-bs-toggle="modal"
            data-bs-target="#yayConfirmationModal"
            v-on:click="handleClick"
            aria-label="Vote Aye, in favor of the motion"
    >Aye <span class="vote-choice-detail">— in favor</span></button>
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
.vote-choice-detail { display: block; font-size: .55em; font-weight: 500; }
</style>
