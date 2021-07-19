<template>
    <div class="card office-setup">
        <div class="card-header">
            <create-office-button></create-office-button>
        </div>

        <div class="card-body">
            <office-list-card></office-list-card>
        </div>

        <div class="card-body">
            <office-edit-card></office-edit-card>
        </div>

        <div class="card-body">
            <p class="card-text">Use the Select buttons to add names from the pool on the left to the list of candidates
                on the right.</p>
            <div class="row">
                <div class="col-lg-6">
                    <candidate-pool-card></candidate-pool-card>
                </div>

                <div class="col-lg-6">
                    <current-candidates-card></current-candidates-card>
                </div>
                <!--        <div class="card-footer">-->
            </div>
        </div>


    </div>
</template>

<script>
import CandidatePoolCard from "./candidate-pool-card";
import CurrentCandidatesCard from "./current-candidates-card";
import OfficeEditCard from "./office-edit-card";
import OfficeListCard from "./office-list-card";
import CreateOfficeButton from "./controls/create-office-button";

export default {
    name: "office-setup-card",
    components: {CreateOfficeButton, OfficeListCard, OfficeEditCard, CurrentCandidatesCard, CandidatePoolCard},
    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
