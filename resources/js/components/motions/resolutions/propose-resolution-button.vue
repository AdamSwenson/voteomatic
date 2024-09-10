<script>
import ButtonParent from "../../parents/button-parent";
import Payload from "../../../models/Payload";

export default {
    name: "propose-resolution-button",
    components: {},
    extends: ButtonParent,
    props: [],

    mixins: [],

    data: function () {
        return {
            label: 'Move Resolution',
            styling: ' btn-danger',
            clicked: false,
        }
    },

    asyncComputed: {

        draftMotion: function () {
            return this.$store.getters.getDraftMotion;
        }
    },

    computed: {
        /**
         * Controls whether the spinner is shown
         * @returns {boolean}
         */
        isWorking: function () {
            return this.clicked;
        },

    },
    methods: {
        handleClick: function () {
            let me = this;

            this.clicked = true;

            //we need to set the formattedContent to
            //have the initial content to avoid the problem
            //discussed in the comments to VOT0-197
            let p = Payload.factory({
                    'object': this.draftMotion,
                    'updateProp': 'formattedContent',
                    'updateVal': this.draftMotion.content
                }
            );
            this.$store.dispatch('updateDraftMotion', p);

            //Now that everything is set properly, we can create it
            this.$store.dispatch('createMotionFromDraft').then(() => {
                //clear draft motion and hide the window.
                // me.requestResetEditingCard();
                me.clicked = false;
                me.$store.commit('clearDraftMotion');

            });
        }
    }

}
</script>

<style scoped>

</style>
