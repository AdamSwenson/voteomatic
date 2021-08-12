
<script>
import ButtonParent from "../../parents/button-parent";

export default {
    name: "propose-amendment-button",
    components: {},
    extends: ButtonParent,
    props: [],

    mixins: [],

    data: function () {
        return {
            label: 'Propose Amendment',
            styling: ' btn-danger',
            clicked : false,
        }
    },

    computed: {
        /**
         * Controls whether the spinner is shown
         * @returns {boolean}
         */
        isWorking : function (){
            return this.clicked;
        },

    },
    methods: {
        handleClick: function () {
            this.clicked=true;
            this.$emit('propose-amendment');
        }
    }

}
</script>

<style scoped>

</style>
