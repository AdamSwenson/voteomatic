<script>
import ButtonParent from "../../../../parents/modal-button-parent";

export default {
    name: "import-pool-button",
    extends: ButtonParent,

    props: [],

    mixins: [],
    data: function () {
        return {
            label: 'Import people',
            modalId: 'import-pool-modal',
            icon: 'bi-upload',
            ariaDisabled: false
        }
    },

    computed: {},


}
</script>

<style scoped>

</style>
