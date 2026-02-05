
<script>
import ButtonParent from "../../parents/button-parent";

export default {
    name: "amendment-button",
    components: {},
    extends: ButtonParent,
    props: [],

    mixins: [],

    data: function () {
        return {
            label: 'Create amendment',
            styling: ' btn-outline-primary amendment-button me-2',
            //dev added in VOT-288 to get rid of warning that icon not defined during render
            icon: ''
        }
    },

    computed: {},
    methods: {
        handleClick: function () {
            this.$emit('new-amendment');
        }
    }

}
</script>

<style scoped>

</style>
