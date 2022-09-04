<template>
    <div class="card" >
<!--        style="width: 25rem;">-->

        <div class="card-header">
            <h4 class="card-title">Propositions</h4>
        </div>

<!--        <div class="card-body">-->
        <ul class="list-group list-group-flush">

            <proposition-list-item
                v-for="proposition in propositions"
                :proposition="proposition"
                :key="proposition.id"
                v-on:edit-requested="requestEdit"
            ></proposition-list-item>

        </ul>
<!--            </div>-->


        <div class="card-footer">
            <create-proposition-button
                v-on:create-motion-clicked="requestNewMode"></create-proposition-button>
        </div>

    </div>

</template>

<script>

import PropositionListItem from "./proposition-list-item";
import CreatePropositionButton from "./create-proposition-button";
export default {
    name: "proposition-list-card",
    components: {CreatePropositionButton, PropositionListItem},
    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        propositions : function(){
            return  this.$store.getters.getElectionPropositions;
        }
    },

    computed: {},

    methods: {
        requestEdit : function(){
            this.$emit('edit-requested');
        },

        requestNewMode : function(){
            this.$emit('new-mode-requested')
        }
    }

}
</script>

<style scoped>

</style>
