
<script>
import ButtonParent from "../../parents/button-parent";
export default {
    name: "clear-draft-motion-button",
    components: {},

    extends : ButtonParent,

    props: [],

    mixins: [],

    data: function () {
        return {
            label : "Clear draft motion",
            styling : " btn-warning "
        }
    },

    asyncComputed: {},

    computed: {},

    methods: {
        handleClick : function(){
            this.$store.commit('clearDraftMotion');
            //hide the dialog

        }
    }

}
</script>

<style scoped>

</style>
