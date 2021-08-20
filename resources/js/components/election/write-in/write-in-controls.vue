<template>
    <div class="write-in-controls">
        <write-in-modal></write-in-modal>
        <write-in-button></write-in-button>
<!--        <button class="btn btn-info" v-on:click="handleNewWriteIn"-->
<!--        >Add write in candidate</button>-->
<!--        <button class="btn btn-danger" v-on:click="handleClear">Remove write in</button>-->
    </div>
</template>

<script>
import WriteInModal from "./write-in-modal";
import WriteInButton from "./write-in-button";

export default {
    name: "write-in-controls",
    components: {WriteInButton, WriteInModal},
    props: [],


    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {},

    methods: {
        handleNewWriteIn: function () {
            // window.console.log('add writein');
            //
            // let d = {
            //     name: '',
            //     info: '',
            //     motionId: this.motion.id
            // };
            //
            // this.$store.dispatch('addWriteInCandidateToOfficeElection', d).then(() => {
            //
            // });

        },

        handleClear: function () {
            window.console.log('clear writein');
        }


    }

}
</script>

<style scoped>

</style>
