<template>
    <div class="overselection-warning">
    <div v-if="showOverSelectionWarning"
         class="alert alert-danger"
         role="alert">
        You may only select {{maxWinners}} for this position.
        Please de-select {{numOver}} {{pluralized}}.
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

    selectedCandidates : function(){
        return this.$store.getters.getSelectedCandidatesForActiveMotion
    },
    numSelected: function(){
        return this.$store.getters.getSelectedCandidatesForActiveMotion.length;
    },

    numOver: function(){
        return this.numSelected - this.maxWinners;
    },

    pluralized: function(){
      if(this.numOver === 1) return 'candidate';

        return 'candidates'
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
