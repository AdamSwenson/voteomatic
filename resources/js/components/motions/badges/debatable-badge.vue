<template>
                    <span
                        v-if="show"
                        class="badge badge-warning"
                    >{{ labelText }}</span>

</template>

<script>
import MotionObjectMixin from "../../../mixins/motionObjectMixin";
export default {
name: "debatable-badge",

props : ["motion"],

mixins : [MotionObjectMixin],

data : function(){
    return {}
},

asyncComputed : {

    show : function(){
        //will be null if hasn't been set
        return  ! _.isNull(this.debatable) && ! _.isUndefined(this.debatable);
    },

    debatable : function(){
        return this.motion.debatable;
    },

    labelText : function(){
        if(_.isNull(this.debatable)) return ''
        if(this.debatable) return "Debatable"
        return "Not debatable"
    }
}

}
</script>

<style scoped>

</style>
