
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
            styling: ' btn-outline-primary amendment-button'
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
