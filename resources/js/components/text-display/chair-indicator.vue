<template>

<div class="row chair-indicator"
v-if="isChair"
>
    <div class="col">
        <div class="alert alert-primary text-center" role="alert">
            Chair
        </div>
    </div>
</div>

</template>

<script>
export default {
name: "chair-indicator",

props : [],

mixins : [],

data : function(){
    return {}
},

asyncComputed : {
    isChair : function(){
        return this.$store.getters.getIsAdmin;
    }
}

}
</script>

<style scoped>
.chair-indicator{
    margin-top: 3em;
}
</style>
