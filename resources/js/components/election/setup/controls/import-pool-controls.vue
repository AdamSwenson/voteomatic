<template>
    <div class="import-pool-controls">
        <import-pool-button></import-pool-button>
        <import-pool-modal>
            <file-import-control></file-import-control>
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
        return {}
    },

    asyncComputed: {},

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
