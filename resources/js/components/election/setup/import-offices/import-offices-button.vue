<script>
import ButtonParent from "../../../parents/modal-button-parent";

export default {
    name: "import-offices-button",
    extends: ButtonParent,

    props: [],

    mixins: [],
    data: function () {
        return {
            label: 'Import offices from file',
            modalId: 'import-offices-modal',
            ariaDisabled: false
        }
    },

    computed: {},


}
</script>

<style scoped>

</style>
