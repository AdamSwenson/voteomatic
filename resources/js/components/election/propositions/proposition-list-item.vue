
<script>
import CardListItemParent from "../../parents/card-list-item-parent";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "proposition-list-item",
    components: {},

    extends : CardListItemParent,

    props: ['proposition'],

    mixins: [MotionStoreMixin],

    data: function () {
        return {
            showRow : true
        }
    },

    asyncComputed: {
        content : function(){
            return this.proposition.displayName;
        },

        buttonLabel : function(){
            return 'Edit';
        },

        isSelected : function(){

          if(!isReadyToRock(this.motion)) return false;

          return this.motion.id === this.proposition.id;
        },

        showButton : function(){
            return ! this.isSelected;
        },

        styling : function(){
            return 'btn-outline-primary'
        }
    },

    computed: {

    },

    methods: {
        handleClick : function(){
            window.console.log('edit proposition clicked', this.proposition);
            //Selects the proposition for editing
            this.motion = this.proposition;
            this.$emit('edit-requested');
        }
    }

}
</script>

<style scoped>

</style>
