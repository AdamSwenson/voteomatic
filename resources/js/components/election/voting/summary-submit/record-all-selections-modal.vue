<script>
import ModalParent from '../../../parents/modal-parent';

export default {
    name: "record-all-selections-modal",

    props: [],
    extends: ModalParent,

    mixins: [],

    data: function () {
        return {
            modalId: 'recordAllSelectionsModal',
            modalTitle: 'Record your votes',
            buttonLabel: 'Record',
            modalText: "Once you click 'Record' your selections will be sent to the server. You will not be able to change your votes.",

        }
    },

    asyncComputed: {
        hasProblem: function () {
            return !this.$store.getters.isBallotErrorFree;
        },

        hideActionButton: function () {
            return this.hasProblem;
        },


        modalSecondaryText: function () {
            if (!this.hasProblem) return '';

            if (this.problemOffices.length > 0) {
                let msg = '<p class="text-danger">There is a problem with your selections for these offices: </p>';
                msg += '<ul>';
                _.forEach(this.problemOffices, (motion) => {
                    msg += '<li class="text-danger">' + motion.content + '</li>';
                });
                msg += '</ul>';
                msg += '<p class="text-danger">Please fix them before submitting</p>';
                return msg;
            }


            //Generic error message fallback
            return '<p class="text-danger">' +
                'There is a problem with your ballot. Please fix it before submitting. </p>';
        },

        problemOffices: function () {
            return this.$store.getters.getOfficesWithErrors;
        }
    },

    computed: {},

    methods: {
        handleClick: function () {
            if (!this.hasProblem) {
                // this.$store.dispatch('castAllElectionVotes')
                this.$store.dispatch('castElectionVotesForSelections');
            }
        }
    }

}
</script>

<style scoped>

</style>
