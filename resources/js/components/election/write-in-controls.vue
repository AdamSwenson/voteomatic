<template>
    <div class="write-in-controls">
        <button class="btn btn-info" v-on:click="handleNewWriteIn"
        >Add write in candidate</button>
<!--        <button class="btn btn-danger" v-on:click="handleClear">Remove write in</button>-->
    </div>
</template>

<script>
import MotionMixin from "../../mixins/motionStoreMixin";

export default {
    name: "write-in-controls",

    props: [],

    mixins: [MotionMixin],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {},

    methods: {
        handleNewWriteIn: function () {
            window.console.log('add writein');

            let d = {
                name: '',
                info: '',
                motionId: this.motion.id
            };

            this.$store.dispatch('addWriteInCandidateToOfficeElection', d).then(() => {

            });

        },

        handleClear: function () {
            window.console.log('clear writein');
        }


    }

}
</script>

<style scoped>

</style>
