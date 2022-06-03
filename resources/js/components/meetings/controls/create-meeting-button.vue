
<script>
import ButtonParent from "../../parents/button-parent";
import ModeMixin from "../../../mixins/modeMixin";
export default {
    name: "create-meeting-button",
    extends: ButtonParent,
    props: [],

    mixins: [ModeMixin],


    data: function () {
        return {

                label : "New meeting",
                styling: 'btn-primary',
            icon: 'bi-plus'
        }
    },

    asyncComputed: {},

    computed: {},

    methods: {

        handleClick: function () {
            let me = this;
            let p = this.$store.dispatch('createMeeting');
            p.then(function () {
                me.setShowArea('create');
          //      me.showFields = true;
            //    me.showArea = 'create';
            })
        },
    }

}
</script>

<style scoped>

</style>
