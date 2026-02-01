<script>
import buttonParent from '../../parents/button-parent';

export default {
    name: "force-page-reload-button",

    extends: buttonParent,

    props: ['motion'],

    mixins: [],

    data: function () {
        return {
            label: 'Force page reloads',
            styling: ' btn-danger '
        }
    },


    computed: {},

    methods: {

        handleClick: function () {
            this.$store.dispatch('orderPageReload');
        }

    }
}
</script>

<style scoped>

</style>
