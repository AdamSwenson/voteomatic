<script>
import ButtonParent from "../../../parents/modal-button-parent";

export default {
    name: "import-pool-button",
    extends: ButtonParent,

    props: [],

    mixins: [],
    data: function () {
        return {
            label: 'Import pool from file',
            modalId: 'import-pool-modal'
        }
    },

    computed: {},


}
</script>

<style scoped>

</style>
