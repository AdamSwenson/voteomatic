<template>
    <div class="candidate-row card mb-3" style="max-width: 540px;">
        <div class="row no-gutters">
            <div class="col-md-4">

                <candidate-button :candidate="candidate"></candidate-button>

            </div>

            <div class="col-md-8">
                <div class="card-body">
                    <writein-input :candidate="candidate" ></writein-input>
                </div>


            </div>
        </div>
    </div>

</template>

<script>
import CandidateButton from "./candidate-button";
import WriteinInput from "./writein-input";
import MotionMixin from "../../mixins/motionStoreMixin";

export default {
    name: "writein-row",

    components: {WriteinInput, CandidateButton},

    props: ['candidate'],

    mixins: [MotionMixin],

    data: function () {
        return {}
    },

    asyncComputed: {

    },

    computed: {},

    methods: {
        // handleNewWriteIn : function(){
        //     window.console.log('add writein');
        //
        //     let d = {
        //         name : '',
        //         info : '',
        //         motionId : this.motion.id
        //     };
        //
        //     this.$store.dispatch('addWriteInCandidateToOfficeElection', d).then(() => {
        //
        //
        //     });
        //
        //
        //
        //
        //
        // },
        //
        // handleClear : function(){
        //     window.console.log('clear writein');
        // }

    }

}
</script>

<style scoped>

</style>
