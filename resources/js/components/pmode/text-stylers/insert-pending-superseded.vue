


<script>
import TextStylerParentRegular from "./text-styler-parent-regular";
export default {
    name: "insert-pending-superseded",
    extends: TextStylerParentRegular,
    props: [
        // 'text',
        'amendmentId'],

    mixins: [],

    data: function () {
        return {
            containerStyle: ' border border-warning border-2 ',
            textStyling: "text-decoration-underline ",
            icon: 'bi-pencil',
            iconStyling: "text-warning"
        }
    },

    // asyncComputed: {},
    //
    // computed: {
    //     containerStyling: function () {
    //         return this.name + this.containerStyle;
    //     }
    // },
    //
    // methods: {}

}
</script>

<style scoped>

</style>
