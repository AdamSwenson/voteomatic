<template>
    <li class="list-group-item ">

<!--        <motion-text-display-->
<!--            v-if="motion.isResolution"-->
<!--            :motion="motion"-->
<!--            motion-style=""-->
<!--            :truncate-resolutions="true"-->
<!--        ></motion-text-display>-->

        <h5 class="motion-text h5">{{ motionText }}</h5>

        <ul v-if="isElection">
            <li class="candidates" v-for="c in candidates" :id="c.id">{{ c.nameAndInfo }}</li>
        </ul>

        <p class="receipt user-select-all">{{ receipt }}</p>
    </li>

</template>

<script>
import {isReadyToRock} from "../../utilities/readiness.utilities";
import ModeMixin from "../../mixins/modeMixin";
import MotionTextDisplay from "../motions/text-display/motion-text-display.vue";

export default {
    name: "receipt-list-item",
    components: {MotionTextDisplay},

    props: ['voteObject'],

    mixins: [ModeMixin],

    data: function () {
        return {}
    },

    computed: {
        // asyncComputed: {

        motion: function () {
            return this.$store.getters.getMotionById(this.voteObject.motionId);
        },

        motionText: function () {
            if (!isReadyToRock(this.motion)) return '';

            //Resolutions and amendments are going to be too complicated, so
            //they will just get a generic name
            if(this.motion.isResolution){
                if(this.motion.isAmendment()) return `Resolution - ${this.motion.type}`;
                if(isReadyToRock(this.motion.info, 'title')) return this.motion.info.title

                return 'Resolution';
            }

            //The motion itself will decide what to show (name if proposition, content if motion)
            return this.motion.displayName;

        },

        receipt: function () {
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
        candidates: function () {
            if (!this.isElection) return [];

            if (isReadyToRock(this.motion) && this.voteObject.receipt) {
                return this.$store.getters.getSelectedCandidatesForMotion(this.motion);
            }
        }

    },


    methods: {}

}
</script>

<style scoped>

</style>
