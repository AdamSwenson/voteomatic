<template>
    <div class="card office-list-card" >
<!--        <div class="card" style="width: 25rem;">-->
        <div class="card-header">
            <div class="h4 card-title">Offices</div>
        </div>

        <!--        <div class="card-body">-->
        <ul class="list-group list-group-flush">

            <office-list-item
                v-for="office in offices"
                :office="office"
                :key="office.id"
            ></office-list-item>

        </ul>
        <!--            </div>-->

        <div class="card-footer">
            <div class="row">
                <div class="col-md-auto text-start">
                    <create-office-button></create-office-button>
                </div>
                <div class="col-md-auto ">
                    <import-offices-controls></import-offices-controls>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
import OfficeListItem from "./office-list-item";
import CreateOfficeButton from "./create/create-office-button";
import ImportOfficesControls from "./import/import-offices-controls";

export default {
    name: "office-list-card",
    components: {ImportOfficesControls, CreateOfficeButton, OfficeListItem},
    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        offices: function () {
            return this.$store.getters.getElectionOffices;
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
