<template>
<!--    <a href="#"-->
       <li class="list-group-item "
    >
        <p class="motion-text ">{{motionText}}</p>
           <ul v-if="isElection">
               <li class="candidates" v-for="c in candidates" :id="c.id">{{c.nameAndInfo}}</li>
           </ul>

        <p class="receipt user-select-all">{{receipt}}</p>
       </li>
<!--    </a>-->

</template>

<script>
import {isReadyToRock} from "../../utilities/readiness.utilities";
import ModeMixin from "../../mixins/modeMixin";

export default {
    name: "receipt-list-item",

    props: ['voteObject'],

    mixins : [ModeMixin],

    data: function () {
        return {}
    },

    asyncComputed: {

        motion : function(){
            return this.$store.getters.getMotionById(this.voteObject.motionId);
        },

        motionText : function(){
          if(! isReadyToRock(this.motion)) return '';

          if(this.motion.type === 'proposition' && isReadyToRock(this.motion, 'info')) return this.motion.info.name;

          return this.motion.content;
        },

        receipt : function(){
            return this.voteObject.receipt;
        },

        /**
         * List of candidate objects representing those who were selected
         *
         * NB., this does not pull the candidates based on the receipt since
         * right now, only one receipt will be returned even though multiple candidates
         * may have been selected.
         *
         * dev This will be fixed in VOT-150
         *
         * @returns {*[]}
         */
        candidates: function(){
            if(! this.isElection ) return [];

            if(isReadyToRock(this.motion) && this.voteObject.receipt){
                return  this.$store.getters.getSelectedCandidatesForMotion(this.motion);
            }
        }

    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
