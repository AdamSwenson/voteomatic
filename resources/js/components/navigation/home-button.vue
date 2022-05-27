<script>
import ButtonParent from "../parents/button-parent";

export default {
    name: "logout-button",
    extends: ButtonParent,
    props: [],

    mixins: [],

    data: function () {
        return {
            label: 'Home',
            styling: ' btn-primary btn-lg'
        }
    },

    asyncComputed: {},

    computed: {},

    methods: {
        handleClick: function () {
            this.$store.dispatch('openHomePage');

        }
    }

}
</script>

<style scoped>

</style>
