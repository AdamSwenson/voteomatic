<template>
<!--    <a href="#"-->
       <li class="list-group-item "
    >
        <p class="motion-text ">{{motionText}}</p>

        <p class="receipt user-select-all">{{receipt}}</p>
       </li>
<!--    </a>-->

</template>

<script>
import {isReadyToRock} from "../../utilities/readiness.utilities";

export default {
    name: "receipt-list-item",

    props: ['voteObject'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {

        motion : function(){
            return this.$store.getters.getMotionById(this.voteObject.motionId);
        },

        motionText : function(){
          if(isReadyToRock(this.motion)) return this.motion.content;
          return ''
        },

        receipt : function(){
            return this.voteObject.receipt;
        }

    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
