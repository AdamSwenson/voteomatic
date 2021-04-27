
<script>
import buttonParent from "../../parents/button-parent";

export default {
    name: "create-election-button",
extends : buttonParent,
    props: [],

    mixins: [],

    data: function () {
        return {
            label : "New election",
            styling: 'btn-primary',
        }
    },


    methods: {
        handleClick : function(){
            window.console.log('Create election clicked');
            this.$store.dispatch('createElection');

        }
    }

}
</script>

<style scoped>

</style>
