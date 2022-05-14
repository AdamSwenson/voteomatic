<template>
    <span class="amendment-text-display text-center">
        <span v-html="taggedNewText"></span>
    </span>


</template>

<script>
import Payload from "../../../models/Payload";

import {checkChanges, getTaggedChanges} from '../../../utilities/amendment.utilities';

/**
 * Displays an amendmentText tagged string indicating where
 * changes have been made between the original and the
 * amendment.
 *
 * THIS USUALLY SHOULD NOT BE USED DIRECTLY. USE AMENDMENT-TEXT-DISPLAY
 */
export default {
    name: "regular-amendment-text-display",

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
        return {
            /**
             * Classes to attach to a word for different purposes
             */
            // _tags: {
                // changeStart: "<span class='text-danger altered-text'>",
                // changeStop: "</span>",
                //
                //
                // //todo dev maybe someday
                //
                // //classes for use in secondary amendments
                // secondaryAmendment: {
                //     //The initial amendment text
                //     primary: {
                //         insert: 'primary-insert',
                //         strike: 'primary-strike',
                //         strikeInsert: 'primary-strike-insert'
                //     },
                //     secondary: {
                //         insert: '',
                //         strike: '',
                //         strikeInsert: ''
                //     }
                // },
                //

        //         altered: 'font-monospace',
        //         inserted: 'text-danger',
        //         struck: 'struck',
        //     }
        }
    },

    asyncComputed: {
        //
        // maxIdx: function () {
        //     if (_.isUndefined(this.originalText) || _.isNull(this.originalText)) return ''
        //     if (_.isUndefined(this.amendmentText) || _.isNull(this.amendmentText)) return ''
        //
        //     return (this.splitOrigText.length > this.splitNewText.length) ? this.splitOrigText.length : this.splitNewText.length;
        // },
        //
        //
        // splitOrigText: function () {
        //     if (_.isUndefined(this.originalText) || _.isNull(this.originalText)) return []
        //     return _.words(this.originalText, /[^, ]+/g);
        // },
        //
        // splitNewText: function () {
        //     return _.words(this.amendmentText, /[^, ]+/g);
        // },


        taggedNewText: function () {
            if (_.isUndefined(this.originalText) || _.isNull(this.originalText)) return ''
            if (_.isUndefined(this.amendmentText) || _.isNull(this.amendmentText)) return ''
            let me = this;

            return getTaggedChanges(this.originalText, this.amendmentText, this.tags.inserted, this.tags.struck);

            // let out = [];
            //
            // let changes = checkChanges(this.originalText, this.amendmentText);
            // // window.console.log('change set', changes);
            // if (changes) {
            //
            //     for (let i = 0; i < this.amendmentText.length; i++) {
            //         // window.console.log(changes, i);
            //
            //         // window.console.log(this.tags.changeStart, 'hd');
            //         let w = '';
            //         if (i === changes.startIndex) {
            //             //we are on the first character in the changeset
            //             //so add the starting tag
            //             w += this.tags.changeStart;
            //             // window.console.log('tag', me.tags.changeStart);
            //         }
            //         //add the actual character
            //         w += me.amendmentText[i];
            //         // window.console.log(me.splitNewText[i]);
            //
            //         if (i === changes.stopIndex) {
            //             // window.console.log('stop');
            //             //we are at the end of the changes
            //             //so add the closing tag.
            //             w += me.tags.changeStop;
            //         }
            //
            //         //push it into the list that we will later join
            //         out.push(w);
            //     }
            // }
            //
            // return _.join(out, "");
        }
        //
        // taggedNewText: function () {
        //     if (_.isUndefined(this.originalText) || _.isNull(this.originalText)) return ''
        //     if (_.isUndefined(this.amendmentText) || _.isNull(this.amendmentText)) return ''
        //
        //     let out = "";
        //     //whichever is longer to avoid truncating output
        //     let maxIdx = (this.splitOrigText.length > this.splitNewText.length) ? this.splitOrigText.length : this.splitNewText.length;
        //
        //     for (let i = 0; i < maxIdx; i++) {
        //         if (this.splitNewText[i] !== this.splitOrigText[i]) {
        //             //something has changed
        //             out += " <span class='text-danger'>";
        //             out += this.splitNewText[i];
        //             out += "</span>";
        //         } else {
        //             out += " " + this.splitNewText[i];
        //         }
        //
        //     }
        //
        //     return out;
        // }
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
