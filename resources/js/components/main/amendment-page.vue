<template>

    <div class="amendment-page">


        <amendment-display></amendment-display>

    </div>


</template>

<script>
import AmendmentDisplay from "../motions/amendments/amendment-setup-area";
export default {
name: "amendment-page",
    components: {AmendmentDisplay},
    props : [],

mixins : [],

data : function(){
    return {}
},

computed : {},

    mounted() {
        let p = this.$store.dispatch('initialize');
        p.then(function () {
        });
    }
}

</script>

<style scoped>

</style>
