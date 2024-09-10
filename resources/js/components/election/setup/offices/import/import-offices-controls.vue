<template>
    <div class="import-offices-controls">
        <import-offices-button></import-offices-button>
        <import-offices-modal :should-close="shouldClose">
            <file-import-control
                action-name="createOfficesFromFile"
                v-on:import-complete="notifyToClose"></file-import-control>
             </import-offices-modal>
    </div>
</template>


<script>
import FileImportControl from "../../pool/import/file-import-control";
import ImportOfficesButton from "./import-offices-button";
import ImportOfficesModal from "./import-offices-modal";

/**
 * Holds the button and modal for importing from a file
 */
export default {
    name: "import-offices-controls",
    components: {ImportOfficesModal, ImportOfficesButton, FileImportControl},
    props: [],

    mixins: [],

    data: function () {
        return {
            shouldClose : 0
        }
    },

    asyncComputed: {},

    computed: {},

    methods: {
        notifyToClose : function(){
            this.shouldClose += 1;
            // this.shouldClose = false;

        }
    }

}
</script>

<style scoped>

</style>
