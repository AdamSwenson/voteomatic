<template>

    <div class="chair-indicator"
         v-if="isChair"
    >
        <div class="row">
            <div class="col">
                <div class="alert alert-primary text-center fs-4" role="alert">
                    Chair
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col text-end">
                <force-page-reload-button></force-page-reload-button>
            </div>
        </div>

    </div>

</template>

<script>
import ForcePageReloadButton from "../pmode/controls/force-page-reload-button.vue";

export default {
    name: "chair-indicator",
    components: {ForcePageReloadButton},

    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    computed: {
        isChair: function () {
            return this.$store.getters.getIsAdmin;
        }
    }

}
</script>

<style scoped>
.chair-indicator {
    margin-top: 3em;
}
</style>
