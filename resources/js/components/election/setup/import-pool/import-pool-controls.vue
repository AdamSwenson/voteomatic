<template>
    <div class="import-pool-controls">
        <import-pool-button></import-pool-button>
        <import-pool-modal :should-close="shouldClose">
            <file-import-control action-name="createPoolFromFile"
                                 v-on:import-complete="notifyToClose"
            ></file-import-control>
        </import-pool-modal>
    </div>
</template>


<script>
import ImportPoolButton from "./import-pool-button";
import ImportPoolModal from "./import-pool-modal";
import FileImportControl from "./file-import-control";

/**
 * Holds the button and modal for importing from a file
 */
export default {
    name: "import-pool-controls",
    components: {FileImportControl, ImportPoolModal, ImportPoolButton},
    props: [],

    mixins: [],

    data: function () {
        return {
            shouldClose: 0
        }
    },

    asyncComputed: {},

    computed: {},

    methods: {
        notifyToClose: function () {
            this.shouldClose += 1;
            // this.shouldClose = false;

        }
    }

}
</script>

<style scoped>

</style>
