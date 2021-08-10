<template>

        <!-- Modal -->
        <div class="modal fade"
             id="confirmMotionModal"
             tabindex="-1"
             aria-labelledby="confirmMotionModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmMotionModalLabel">
                            Please confirm that this is the motion you wish to make. You will not be able to edit it after you click 'Make motion'</h5>
                        <button type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <p class="blockquote">{{motionText}}</p>

                        <p v-if="isReady">
                            <required-vote-badge :motion="motion"></required-vote-badge>    <motion-type-badge :motion="motion"></motion-type-badge>
                        </p>
<!--                        <p><strong>Requires:</strong> {{motion.requires}}</p>-->
<!--                        <p>Type: {{motion.type}}</p>-->
<!--&lt;!&ndash;                        <p>Description: {{motion.description}}</p>&ndash;&gt;-->
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                        >Continue editing</button>

                        <button type="button"
                                class="btn btn-primary"
                                data-dismiss="modal"
                                v-on:click="handleClick"
                        >Make motion</button>

                    </div>
                </div>
            </div>
        </div>

    </template>


<script>
import RequiredVoteBadge from "../badges/required-vote-badge";
import MotionTypeBadge from "../badges/motion-type-badge";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
export default {
name: "create-motion-modal",
    components: {MotionTypeBadge, RequiredVoteBadge},
    props : ['motion'],

mixins : [],

data : function(){
    return {}
},

computed : {
    isReady: function(){
      return isReadyToRock(this.motion);
    },
    motionText: function(){
        if(isReadyToRock(this.motion)) return this.motion.content;
        return ''
    }
},
methods: {
    handleClick : function(){
        this.$emit('confirmed');
    }
}
}
</script>

<style scoped>

</style>
