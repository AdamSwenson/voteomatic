
<script>
import ModalParent from "../parents/js-controlled-modal-parent";
import motionSecondMixin from "../../mixins/motionSecondMixin";
import {isReadyToRock} from "../../utilities/readiness.utilities";


export default {
    name: "chair-motion-second-modal",

    extends: ModalParent,

    mixins: [motionSecondMixin],

    data: function () {
        return {
            leftButtonStyling: "btn-danger",
            rightButtonStyling: "btn-success",
            modalId: "chairMotionSecondModal",
            headerText: "It has been moved that",
            rightButtonLabel: "Mark motion seconded",
            leftButtonLabel: "Mark motion as dead"
        }
    },

    asyncComputed: {

        /**
         * Controls whether the modal displays
         * @returns {boolean}
         */
        showModal: {
            get: function () {
                //Make sure it has loaded and has the value true
                if (isReadyToRock(this.isMotionPendingSecond) && this.isMotionPendingSecond === true) {
                    // window.console.log('show');
                    this.openModal();
                    return true;
                } else {
                    return false;
                }
            },
            watch: ['isMotionPendingSecond']
        },

        bodyText: function () {
            return `<p class="blockquote">${this.motionText}</p>

            <p class="second-instruction text-muted">If the motion has been seconded outside of the app (e.g., verbally),
            mark it as seconded. </p>
            <p class="second-instruction text-muted">If sufficient time has passed with no second, mark
            the motion as dead.</p> `;

        },

        motionText: function () {
            if (!isReadyToRock(this.motionPendingSecond) || !this.showModal) return ''
            return this.motionPendingSecond.content;
        },


    },

    computed: {},

    methods: {

        handleLeftClick: function () {
            //They have indicated the motion is dead
            this.$store.dispatch('markNoSecondObtained', this.motionPendingSecond);
            this.$store.dispatch('resetMotionPendingSecond');
            this.closeModal();
        },

        handleRightClick: function () {
            //They have clicked second
            this.$store.dispatch('secondMotion', this.motionPendingSecond);
            this.closeModal();
        },

    }

}
</script>

<style scoped>
.leftButton {
    margin-right: 4em;
}
</style>
