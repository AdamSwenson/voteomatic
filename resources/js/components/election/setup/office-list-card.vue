<template>
    <div class="card" style="width: 25rem;">

        <div class="card-header">
            <div class="h4 card-title">Offices / positions in this election</div>
        </div>

        <ul class="list-group list-group-flush">

            <office-list-item
                v-for="office in offices"
                :office="office"
                :key="office.id"
            ></office-list-item>

        </ul>

    </div>

</template>

<script>
import OfficeListItem from "./office-list-item";
export default {
    name: "office-list-card",
    components: {OfficeListItem},
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
