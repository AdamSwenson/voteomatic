<script>
import chairMixin from "../../mixins/chairMixin";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import RequiredVoteBadge from "./badges/required-vote-badge";
import MotionTypeBadge from "./badges/motion-type-badge";
import DebatableBadge from "./badges/debatable-badge";
import JsControlledModalParent from "../parents/js-controlled-modal-parent";

export default {
    name: "motion-in-order-modal",
    components: {DebatableBadge, MotionTypeBadge, RequiredVoteBadge},
    props: [],

    extends : JsControlledModalParent,

    mixins: [chairMixin],

    data: function () {
        return {
            leftButtonStyling: "btn-secondary",
            rightButtonStyling: "btn-primary",
            modalId: 'motionInOrderModal',
            headerText: "It has been moved that ",
            rightButtonLabel: "Approve",
            leftButtonLabel: "Reject"
        }
    },

    asyncComputed: {


        motion: function () {
            return this.$store.getters.nextMotionNeedingApproval;
        },

        bodyText: function () {
            if (!isReadyToRock(this.motion)) return ''
            // return this.motion.content;
            return `<p className="blockquote" >${this.motion.content}</p>`;

            /*
            dev would be nice to include this too, but would need to add the compiler parent
            <p>-->
<!--                        <required-vote-badge :motion="motion"></required-vote-badge>-->
<!--                        <motion-type-badge :motion="motion"></motion-type-badge>-->
<!--                        <debatable-badge :motion="motion"></debatable-badge>-->
<!--                    </p>-->
             */
        },

        showModal: function () {
            if (this.isOrderAuthority && isReadyToRock(this.motion)) {
                this.openModal();
                // $('#' + this.modalId).modal();
                return true;
            }

        }


    },

    computed: {},

    methods: {

        handleLeftClick: function () {
            this.handleReject();
        },
        handleRightClick: function () {
            this.handleApprove();
        },

        handleApprove: function () {
            this.$store.dispatch('markMotionInOrder', this.motion)
            this.closeModal();
        },

        handleReject: function () {
            this.$store.dispatch('markMotionOutOfOrder', this.motion)
            this.closeModal();
        }
    }

}
</script>

<style scoped>

</style>
