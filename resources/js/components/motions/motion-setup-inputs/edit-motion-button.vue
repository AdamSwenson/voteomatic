<script>
import ButtonParent from "../../parents/button-parent";

export default {
    name: "edit-motion-button",
    components: {},
    extends: ButtonParent,
    props: [],

    mixins: [],

    data: function () {
        return {
            label: 'Edit pending motion',
            styling: " btn-outline-danger"
            }
    },

    computed: {},

    methods: {
        handleClick: function () {
            this.$emit('edit-motion-clicked');
        }
    }

}
</script>

<style scoped>

</style>
