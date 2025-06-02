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
            modalText: "<p>Clicking 'Record' will save your selections. You will <strong>not</strong> be able to change these votes.</p>" +
                "<p>If you selected 0 candidates for a position, you may return to finish voting on that position until the election closes.</p>" +
                "<p>However, if you selected 1 or more candidates but less than the maximum for a position, you will not be able to add more later.</p>" //+
                // "<p>If you did not make a selection for the Bylaws change, you will be able vote on it later</p>",
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
