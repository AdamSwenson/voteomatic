<template>

    <div class="resolution-amendment-text-display"
         v-html="taggedNewText"
    ></div>


</template>

<script>
import HtmlDiff from 'htmldiff-js';
// import {checkChanges, getTaggedChangesOfHtml} from '../../../utilities/amendment.utilities';

/**
 * Displays an amendmentText tagged string indicating where
 * changes have been made between the original and the
 * amendment.
 *
 * THIS USUALLY SHOULD NOT BE USED DIRECTLY. USE AMENDMENT-TEXT-DISPLAY
 */
export default {
    name: "resolution-amendment-text-display",

    // props: ['amendmentText', 'originalText'],

    props: {
        amendmentText: String,
        originalText: String,

        tags: {
            type: Object,
            // Object or array defaults must be returned from
            // a factory function
            default: function () {
                return {
                    altered: 'font-monospace',
                    inserted: 'text-danger',
                    struck: 'struck',
                }
            }
        }
    },

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {

        taggedNewText: function () {

            //dev
            // return this.amendmentText;


            if (_.isUndefined(this.originalText) || _.isNull(this.originalText)) return ''
            if (_.isUndefined(this.amendmentText) || _.isNull(this.amendmentText)) return ''
            let me = this;

            let diffHtml = HtmlDiff.execute(this.originalText, this.amendmentText);
            return diffHtml;
            // return diffHtml.innerHTML;

            // return getTaggedChangesOfHtml(this.originalText, this.amendmentText);
        }

    },

    computed: {}

}
</script>

<style>

/*.altered-text {*/
/*    fw-: bold;*/
/*}*/

/*.struck {*/
/*    text-decoration: line-through;*/
/*}*/

/*ins {*/
/*    text-decoration: underline;*/
/*    background-color: #d4fcbc;*/
/*}*/

/*del {*/
/*    text-decoration: line-through;*/
/*    background-color: #fbb6c2;*/
/*    color: #555;*/
/*}*/

/*!**/
/*Classes added to the primary amendment text when*/
/*displaying a secondary amendment.*/
/**!*/
/*.primary-insert {*/

/*}*/

/*.primary-strike {*/
/*}*/

/*.primary-strike-insert {*/
/*}*/
</style>
