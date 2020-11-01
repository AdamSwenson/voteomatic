<template>

    <li class="list-group-item">
        <motion-select-button
            :motion="motion"
        ></motion-select-button>

        {{ motion.content }}

        <end-voting-button
            v-if="isSelected"
            :motion="motion"
        ></end-voting-button>
    </li>
</template>

<script>
import MotionSelectButton from "./motion-select-button";
import EndVotingButton from "./end-voting-button";

export default {
name: "motion-select-area",
    components: {MotionSelectButton, EndVotingButton},
    props : ['motion'],

    asyncComputed : {

        selectedMotion : function (){
            return  this.$store.getters.getActiveMotion;
        },

        isSelected: function () {

            if (_.isUndefined(this.selectedMotion) || _.isNull(this.selectedMotion)) return false

            return this.motion.id === this.selectedMotion.id
        }


    },
}
</script>

<style scoped>

</style>
