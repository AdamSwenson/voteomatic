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
 * make all motions use html formatted text to limit the attack surface
 * for xss etc
 */
export default {
    name: "resolution-text-display",

    props: ["motion"],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        content : function(){
            if(isReadyToRock(this.motion)) return this.motion.content;
            return '';
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
