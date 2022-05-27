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

    /**
     * Added for VOT-134. Yanks the focus to the top of the screen
     * when an overselection warning appears
     */
    runFocus : function(){
        if(this.showOverSelectionWarning) this.focusAlert();
    },


    showOverSelectionWarning : function(){
        return this.$store.getters.showOverSelectionWarningForActiveMotion;
    }
},

computed : {},

methods : {
    focusAlert : function(){
        document.body.scrollTop = document.documentElement.scrollTop = 0;

    }
}

}
</script>

<style scoped>

</style>
