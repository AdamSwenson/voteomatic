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

                        <h5 class="modal-title" id="confirmMotionModalLabel">{{headerText}}</h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>

                    </div>

                    <div class="modal-body">

                        <p class="blockquote">{{motionText}}</p>

                        <p v-if="isReady">
                            <required-vote-badge :motion="motion"></required-vote-badge>    <motion-type-badge :motion="motion"></motion-type-badge>
                        </p>

                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                        >Continue editing</button>

                        <button type="button"
                                class="btn btn-primary"
                                data-bs-dismiss="modal"
                                v-on:click="handleClick"
                        >{{buttonLabel}}</button>

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
    // modalId : function(){},
    // modalTitle : function(){},
    //   handleClick : function(){},
    //  buttonLabel : function(){},
    isReady: function(){
      return isReadyToRock(this.motion);
    },
    motionText: function(){
        if(isReadyToRock(this.motion)) return this.motion.content;
        return ''
    },
headerText : function(){
    // if(this.motion.type === 'proposition') return "Please confirm that the proposition is correct";

    return "Please confirm that this is the motion you wish to make. You will not be able to edit it after you click 'Make motion'";
},
    /**
     * Allows to use for both regular meeting motions and election propositions
     * dev: Turned out to not be used.
     * @returns {string}
     */
    itemType: function(){
        if(this.motion.type === 'proposition') return 'proposition';
        return 'motion';
    },
    buttonLabel: function (){
        // if(this.motion.type === 'proposition') return 'Save proposition';
        return 'Make motion'
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
