
<script>
import buttonParent from '../../parents/button-parent';

export default {
    name: "mark-passed-button",

    props: ['motion'],
extends: buttonParent,
    mixins: [],

    data: function () {
        return {
                label: 'Mark passed',
                styling : ' btn-success '
        }
    },

    asyncComputed: {},

    computed: {},

    methods: {
        handleClick : function(){
            this.$store.dispatch('markMotionAsPassed', this.motion);
        }
    }

}
</script>

<style scoped>

</style>
