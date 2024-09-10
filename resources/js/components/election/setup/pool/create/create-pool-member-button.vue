
<script>
import ButtonParent from "../../../../parents/modal-button-parent";

export default {
    name: "create-pool-member-button",
    extends: ButtonParent,

    props: [],

    mixins: [],
    data: function () {
        return {
            label: 'Add person',
            modalId: 'create-pool-member-modal',
            ariaDisabled: false,
            styling: 'btn-primary',
            icon: 'bi-plus'
        }
    },

    computed: {},



}
</script>

<style scoped>

</style>
