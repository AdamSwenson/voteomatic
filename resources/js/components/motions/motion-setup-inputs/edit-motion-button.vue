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
            styling: " btn-outline-danger me-2",
            //dev added in VOT-288 to get rid of warning that icon not defined during render
            icon: ''
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
