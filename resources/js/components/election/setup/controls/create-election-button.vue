
<script>
import buttonParent from "../../../parents/button-parent";
import ModeMixin from "../../../../mixins/modeMixin";
export default {
    name: "create-election-button",
extends : buttonParent,
    props: [],

    mixins: [ModeMixin],

    data: function () {
        return {
            label : "New election",
            styling: 'btn-primary',
        }
    },


    methods: {
        handleClick : function(){
            window.console.log('Create election clicked');
            // this.setElection();

            //this actually does all the things to get set up
            //including activating the edit display.
            this.$store.dispatch('setElectionMode');
            // this.$store.dispatch('createElection');

        }
    }

}
</script>

<style scoped>

</style>
