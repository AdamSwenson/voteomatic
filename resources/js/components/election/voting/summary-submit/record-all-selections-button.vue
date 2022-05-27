

<script>
import ModalButtonParent from '../../../parents/modal-button-parent';

export default {
    name: "record-all-selections-button",

    extends: ModalButtonParent,
    props: [],

    mixins: [],

    data: function () {
        return {
            // modalId : 'recordAllSelectionsModal',

            label: 'Record all selections'
        }
    },

    asyncComputed: {
        modalId : function(){
            //prevent from opening modal
            if(this.hasProblem) return '';
            return 'recordAllSelectionsModal';
        },
        hasProblem : function (){
            return ! this.$store.getters.isBallotErrorFree;
        },

        styling: function(){
            if (this.hasProblem) return '  btn-success disabled'

            return '  btn-success'
        },

        ariaDisabled: function () {
            return this.hasProblem;
        },

    },

    computed: {
    },

    methods: {
        handleClick: function(){

        }
    }

}
</script>

<style scoped>

</style>
