<script>
import ModalParent from "../../../parents/modal-parent";

// import MeetingMixin from "../../../../mixins/meetingMixin";
// import ModeMixin from "../../../../mixins/modeMixin";
export default {
    name: "import-pool-modal",
    extends: ModalParent,
    props: ['shouldClose'],

    mixins: [],

    data: function () {
        return {
            modalId: 'import-pool-modal',
            modalTitle: 'Import pool from file',
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
<p>Please ensure that your csv file separates the candidate names into 2 columns.</p>
<p>The first column should contain the headers:</p>
<ul>
<li><strong>First name</strong></li>
<li><strong>Last name</strong></li>
<li>department (optional)</li>
<li>link (optional)</li>
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
