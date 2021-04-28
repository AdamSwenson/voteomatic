<template>
    <div class="card office-setup">

        <div class="card-body">
            <office-edit-card-card></office-edit-card-card>
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
export default {
    name: "office-setup-card",

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
