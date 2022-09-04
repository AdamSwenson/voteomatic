<script>
import ModalParent from "../../../../parents/modal-parent";

// import MeetingMixin from "../../../../mixins/meetingMixin";
// import ModeMixin from "../../../../mixins/modeMixin";
export default {
    name: "import-offices-modal",
    extends: ModalParent,
    props: ['shouldClose'],

    mixins: [],

    data: function () {
        return {
            modalId: 'import-offices-modal',
            modalTitle: 'Import office from file',
            // hideActionButton: true,
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
            return `<p>This allows you to import from a .csv file.</p>
<p>The first column should contain the headers:</p>
<ul>
<li><strong>Office name</strong></li>
<li><strong>Max winners</strong></li>
<li>Description (optional)</li>
</ul>
`;

        }
    },

    methods: {
        handleClick: function () {
            this.closeModal();
        }
    }

}
</script>

<style scoped>

</style>
