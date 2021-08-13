
<script>
import ButtonParent from "../parents/button-parent";
export default {
    name: "logout-button",
    extends: ButtonParent,
    props: [],

    mixins: [],

    data: function () {
        return {
            label: 'Logout',
            styling: ' btn-danger btn-lg'
        }
    },

    asyncComputed: {},

    computed: {},

    methods: {
        handleClick: function(){
this.$store.dispatch('logout');

        }
    }

}
</script>

<style scoped>

</style>
