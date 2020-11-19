<template>
    <blockquote class="blockquote mb-0 amendment-text-display"
                v-html="taggedNewText">
    </blockquote>


</template>

<script>
import Payload from "../../models/Payload";

export default {
    name: "amendment-text-display",

    props: ['amendmentText', 'originalText'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {

        splitOrigText: function () {
            if (_.isUndefined(this.originalText) || _.isNull(this.originalText)) return []
            return _.words(this.originalText, /[^, ]+/g);
        },

        splitNewText: function () {
            return _.words(this.amendmentText, /[^, ]+/g);
        },

        taggedNewText: function () {
            if (_.isUndefined(this.originalText) || _.isNull(this.originalText)) return ''
            if (_.isUndefined(this.amendmentText) || _.isNull(this.amendmentText)) return ''

            let out = "";
            //whichever is longer to avoid truncating output
            let maxIdx = (this.splitOrigText.length > this.splitNewText.length) ? this.splitOrigText.length : this.splitNewText.length;

            for (let i = 0; i < maxIdx; i++) {
                if (this.splitNewText[i] !== this.splitOrigText[i]) {
                    //something has changed
                    out += " <span class='text-danger'>";
                    out += this.splitNewText[i];
                    out += "</span>";
                } else {
                    out += " " + this.splitNewText[i];
                }

            }

            return out;
        }

    },

    computed: {}

}
</script>

<style scoped>

</style>
