<script>
import ButtonParent from "../../../../parents/modal-button-parent";

export default {
    name: "import-offices-button",
    extends: ButtonParent,

    props: [],

    mixins: [],
    data: function () {
        return {
            label: 'Import offices',
            modalId: 'import-offices-modal',

            //dev VOT-288
            // Dealing with: (deprecation ATTR_FALSE_VALUE) Attribute "aria-disabled" with v-bind value `false` will
            // render aria-disabled="false" instead of removing it in Vue 3. To remove the attribute, use `null`
            // or `undefined` instead.
            // ariaDisabled: false,
            ariaDisabled: null,
            icon: 'bi-upload',
            styling : " btn-info"
        }
    },

    computed: {},


}
</script>

<style scoped>

</style>
