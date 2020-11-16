<template>
    <button type="button" class="btn btn-primary btn-lg"
            v-on:click="handleClick"
    >Refresh
    </button>
</template>

<script>
export default {
    name: "refresh-button",

    methods: {
        handleClick : function (){
            location.reload();
        }
    }
}
</script>

<style scoped>

</style>
