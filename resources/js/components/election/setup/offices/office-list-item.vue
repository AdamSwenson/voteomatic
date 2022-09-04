
<script>
import CardListItemParent from "../../../parents/card-list-item-parent";
import MotionStoreMixin from "../../../../mixins/motionStoreMixin";
import {isReadyToRock} from "../../../../utilities/readiness.utilities";

export default {
    name: "office-list-item",
    components: {},

    extends : CardListItemParent,

    props: ['office'],

    mixins: [MotionStoreMixin],

    data: function () {
        return {
            showRow : true
        }
    },

    asyncComputed: {
        content : function(){
            return this.office.content;
        },

        buttonLabel : function(){
            return 'Edit';
        },

        isSelected : function(){

          if(!isReadyToRock(this.motion)) return false;

          return this.motion.id === this.office.id;
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
            window.console.log('edit office clicked', this.office);
            //Selects the office for editing
            this.motion = this.office;
        }
    }

}
</script>

<style scoped>

</style>
