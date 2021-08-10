<script>
import ButtonParent from "../parents/button-parent";

export default {
    name: "open-voting-button",

    extends: ButtonParent,
    props: ['motion'],

    mixins: [],

    data: function () {
        return {
            label: "Start Voting",
            styling: "btn-success btn-lg btn-block"

        }
    },

    asyncComputed: {},

    computed: {},

    methods: {
        handleClick: function () {
            this.$store.dispatch('startVotingOnMotion', this.motion);
        }
    }

}
</script>

<style scoped>

</style>
