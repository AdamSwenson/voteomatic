
<script>
import ModalParent from "../../parents/modal-parent";
import MotionStoreMixin from '../../../mixins/motionStoreMixin'
import {isReadyToRock} from "../../../utilities/readiness.utilities";

/**
 * Note, this will require that the delete-motion-button is
 * included elsewhere on the page. They are linked via  bootstrap
 * using the data-bs-dismiss=modal attribute. They are not linked
 * by vue or vuex events.
 */
export default {
    name: "delete-motion-modal",

    props: [],

    extends : ModalParent,
    mixins: [MotionStoreMixin],

    data: function () {
        return {
            hideActionButton: false,
            modalId: 'deleteMotionModal',
            buttonLabel: 'Yes. Delete it.',
            modalSecondaryText: ''

        }
    },


    computed: {
    // asyncComputed: {
        modalText: function () {
            if(!isReadyToRock(this.motion)) return '';

            let type = this.motion.type === 'proposition' ? 'proposition' : 'motion';
            return `<p> You are about to permanently delete this ${type} and any votes associated with it.</p>
                    <p>This cannot be undone</p>
                    <p>Are you sure?</p>`
        },

        modalTitle: function () {
            if(!isReadyToRock(this.motion)) return 'Delete Motion';
            if (this.motion.type === 'proposition') return 'Delete Proposition';
            return 'Delete Motion'
        }
    },

    methods: {
        handleClick: function () {

            let me = this;

            //First we create and store a new motion from the
            //provided template
            let p = this.$store.dispatch('deleteMotion', me.motion)
                .then(function () {
                });
        }

    }


}

</script>

<style scoped>

</style>
