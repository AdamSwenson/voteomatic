
<script>
import buttonParent from "../../../../parents/button-parent";
import MeetingMixin from "../../../../../mixins/meetingMixin";
import ModeMixin from "../../../../../mixins/modeMixin";

export default {
    name: "delete-candidate-field-button",

    props: ['fieldName'],
    extends: buttonParent,

    mixins: [MeetingMixin, ModeMixin],


    data: function () {
        return {
            label: 'Delete',
            styling: 'btn-danger'
            //isWorking : false
        }
    },

    asyncComputed: {},

    computed: {},

    methods: {
        handleClick: function(){
            this.$store.dispatch('deleteCandidateField', this.fieldName);
        }
    }

}
</script>

<style scoped>

</style>
