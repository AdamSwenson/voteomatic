
<script>
import buttonParent from '../../parents/button-parent';

export default {
    name: "mark-failed-button",
extends: buttonParent,

    props: ['motion'],

    mixins: [],

    data: function () {
        return {
            label: 'Mark failed',
            styling : ' btn-danger '
        }
    },


    computed: {},

    methods: {

        handleClick: function () {
            this.$store.dispatch('markMotionAsFailed', this.motion);
        }

    }

}
</script>

<style scoped>

</style>
