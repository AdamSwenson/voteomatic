<script>
import ButtonParent from "../../../parents/modal-button-parent";

export default {
    name: "import-offices-button",
    extends: ButtonParent,

    props: [],

    mixins: [],
    data: function () {
        return {
            label: 'Import offices',
            modalId: 'import-offices-modal',
            ariaDisabled: false,
            icon: 'bi-upload'
        }
    },

    computed: {},


}
</script>

<style scoped>

</style>
