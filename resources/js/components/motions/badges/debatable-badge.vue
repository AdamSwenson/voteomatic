<template>
                    <span
                        v-if="show"
                        class="badge bg-warning"
                    >{{ labelText }}</span>

</template>

<script>
import MotionObjectMixin from "../../../mixins/motionObjectMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

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
        if(isReadyToRock(this.motion)) return this.motion.debatable;
    },

    labelText : function(){
        if(_.isNull(this.debatable)) return ''
        if(this.debatable) return ""
        return "No debate allowed"
    }
}

}
</script>

<style scoped>

</style>
