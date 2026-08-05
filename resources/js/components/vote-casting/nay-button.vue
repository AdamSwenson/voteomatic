<template>
    <div class="d-grid gap-2">
    <button type="button"
            class="btn btn-danger text-light vote-choice"
            data-bs-toggle="modal"
            data-bs-target="#nayConfirmationModal"
            v-on:click="handleClick"
            aria-label="Vote Nay, against the motion"
    ><span class="nayText">Nay</span><span class="vote-choice-detail">— against</span>
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
.vote-choice-detail { display: block; font-size: .55em; font-weight: 500; }
</style>
