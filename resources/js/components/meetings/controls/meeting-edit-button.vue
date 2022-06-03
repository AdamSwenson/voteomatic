<script>
import ButtonParent from "../../parents/button-parent";
import ModeMixin from "../../../mixins/modeMixin";

export default {
    name: "meeting-edit-button",
    extends: ButtonParent,
    props: [],


    mixins: [ModeMixin],


    data: function () {
        return {

            label: "Edit current",
            styling: 'btn-warning',
            icon: 'bi-pencil'
        }
    },

    asyncComputed: {},

    computed: {},

    methods: {
        handleClick: function () {
            this.setSetup();
            this.setShowArea('edit');
            this.$emit('showArea', 'edit');

        }
    }

}
</script>

<style scoped>

</style>
