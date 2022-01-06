<template>
    <div class="card" style="width: 25rem;">

        <div class="card-header">
            <div class="h4 card-title">Offices in this election</div>
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
            <create-office-button></create-office-button>
        </div>

    </div>

</template>

<script>
import OfficeListItem from "./office-list-item";
import CreateOfficeButton from "./controls/create-office-button";
export default {
    name: "office-list-card",
    components: {CreateOfficeButton, OfficeListItem},
    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        offices : function(){
            return  this.$store.getters.getMotions;
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
