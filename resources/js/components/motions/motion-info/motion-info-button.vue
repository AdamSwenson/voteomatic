<script>
import ButtonParent from "../../parents/button-parent";
import {isReadyToRock} from "../../../utilities/readiness.utilities";


export default {
    name: "motion-info-button",
    components: {},
    extends : ButtonParent,

    props: ["template", "selectedTemplate"],

    mixins: [],

    data: function () {
        return {
            icon: ''
        }
    },

    computed: {

        label: function () {
          if(isReadyToRock(this.template)) return this.template.name;
          return ''
        },

        isSelected: function () {
            return this.selectedTemplate === this.template;
        },

        styling : function(){
            if( this.isSelected) return " btn-primary motion-info-button";

            return " btn-outline-primary motion-info-button";
        }

    },
    methods: {

        handleClick: function () {
window.console.log('motion-info-button', 'handleClick', 68, this.template);
            this.$emit("template-select", this.template);

        }
    }

}
</script>

<style scoped>
.motion-info-button {
    margin-right: 1em;
    margin-top: 1em;
}

</style>
