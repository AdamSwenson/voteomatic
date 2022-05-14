<template>

    <div
        class="resolution-text-display"
         v-html="content">
    </div>


</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";

/**
 * This displays the text of a resolution. NB, we don't just
 * make all motions use amendmentText formatted text to limit the attack surface
 * for xss etc
 */
export default {
    name: "resolution-text-truncated-display",

    props: ["motion"],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        content : function(){
            let out = '';
            if(! isReadyToRock(this.motion)) return out;
            out += '<h5>';
            if(isReadyToRock(this.motion, 'resolutionIdentifier')){
                out += '(' + this.motion.resolutionIdentifier +') ';
            }
            out += this.motion.title;
            out += '</h5>';

            return out;
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
