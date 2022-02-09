<template>
    <div class="overselection-warning">
    <div v-if="showOverSelectionWarning"
         class="alert alert-danger"
         role="alert">
        You may only select {{maxWinners}} for this position.
        Please de-select {{numOver}} candidates.
    </div>

    </div>
</template>

<script>
export default {
name: "overselection-warning",

props : [],

mixins : [],

data : function(){
    return {}
},

asyncComputed : {
    maxWinners : function(){
        return this.$store.getters.getMaxWinners;
    },

    numSelected: function(){
        return this.$store.getters.getSelectedCandidatesForMotion.length;
    },

    numOver: function(){
        return this.numSelected - this.maxWinners;
    },


    showOverSelectionWarning : function(){
        return this.$store.getters.showOverSelectionWarningForActiveMotion;
    }
},

computed : {},

methods : {}

}
</script>

<style scoped>

</style>
