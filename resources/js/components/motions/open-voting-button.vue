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

            clicked : false,
        }
    },

    asyncComputed: {},

    computed: {
        /**
         * Controls whether the spinner is shown
         * @returns {boolean}
         */
        isWorking : function (){
            return this.clicked;
        },

        styling : function(){
            // if( this.clicked) return "btn-outline-success btn-lg btn-block";
            return "btn-success btn-lg btn-block";
        }

    },

    methods: {
        handleClick: function () {
            this.clicked=true;
            this.$store.dispatch('startVotingOnMotion', this.motion);
        }
    }

}
</script>

<style scoped>

</style>
