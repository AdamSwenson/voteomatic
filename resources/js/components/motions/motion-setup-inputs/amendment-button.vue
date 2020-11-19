<template>
    <button type="button"
            v-bind:class="styling"
            v-on:click="handleClick"
    >Create amendment</button>

</template>

<script>
export default {
name: "amendment-button",

props : [],

mixins : [],

data : function(){
    return {
        styling : 'btn btn-outline-primary'
    }
},

computed : {},
    methods : {
        handleClick : function(){
this.$emit('new-amendment');
        }
    }

}
</script>

<style scoped>

</style>
