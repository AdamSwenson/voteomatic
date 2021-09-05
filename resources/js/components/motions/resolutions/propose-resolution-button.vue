<script>
import ButtonParent from "../../parents/button-parent";

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
