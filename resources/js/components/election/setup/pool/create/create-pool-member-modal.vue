<script>
import ModalParent from "../../../../parents/modal-parent";

export default {
    name: "create-pool-member-modal",
    extends: ModalParent,
    props: ['shouldClose'],

    mixins: [],

    data: function () {
        return {
            modalId: 'create-pool-member-modal',
            modalTitle: 'Add someone to the list of potential candidates',
            hideActionButton: true,
            buttonLabel: 'Done'

        }
    },

    watch : {
        shouldClose: function(){
            // if(this.shouldClose === true){
                this.closeModal();
            // }
        }
    },


    computed: {
        modalText: function () {
        }
    },

    methods: {
        handleClick: function () {
            // this.closeModal();
        }
    }

}
</script>

<style scoped>

</style>
