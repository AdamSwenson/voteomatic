// module.exports = {
//
//     methods: {
//
// import {amendmentType} from "../mixins/amendmentMixin";

let _ = require('lodash');

// import HtmlDiff from 'htmldiff-js';
//
// const insertTag = '<ins class="diffins">';
// const strikeTag = '<del class="diffdel">';
// const insertRegex = new RegExp(insertTag);
// const strikeRegex = new RegExp(strikeTag);
// const insertContentRegex = new RegExp('(?<=' + insertTag + ')(.*?)(?=</ins>)');
// const strikeContentRegex = new RegExp('(?<=' + strikeTag + ')(.*?)(?=</del>)');
//
// /**
//  * Determines the type of amendment.
//  * Possible returns:
//  *      strike
//  *      insert
//  *      strikeinsert
//  * @returns {string|boolean}
//  */
// module.exports.amendmentType = (diffTaggedText) => {
//     let out = '';
//     if (strikeRegex.test(diffTaggedText)) {
//         out += 'strike';
//     }
//     if (insertRegex.test(diffTaggedText)) {
//         out += 'insert'
//     }
//     return out;
// };
//
// /**
//  * Returns the amendment text tagged with
//  * <ins class="diffins">  and
//  * <del class="diffdel">
//  * @returns {string|*|string}
//  */
// module.exports.diffTagText = (originalText, amendmentText) => {
//     if (_.isUndefined(originalText) || _.isNull(originalText)) return ''
//     if (_.isUndefined(amendmentText) || _.isNull(amendmentText)) return ''
//
//     let diffHtml = HtmlDiff.execute(originalText, amendmentText);
//     return diffHtml;
//     // (?<=<pre>)(.*?)(?=</pre>)
// };
//
// module.exports.getChangedText = (diffTaggedText, type) => {
//     //dev todo having trouble with using amendment type so temp made a param
//     // let type = amendmentType(diffTaggedText);
//     let r;
//     switch (type) {
//         case 'insert' :
//           return insertContentRegex.exec(diffTaggedText);
//             break;
//         case 'strike':
//             return strikeContentRegex.exec(diffTaggedText);
//             break;
//         case 'strikeinsert':
//             break;
//     }
//
// };


// ================================== OLD


const Diff = require('diff');
// const HtmlDiff = require('htmldiff-js');
// import HtmlDiff from 'htmldiff-js';

/**
 * Tags plaintext changes
 * @param orig
 * @param amend
 * @param addedTag
 * @param removedTag
 * @returns {string}
 */
module.exports.getTaggedChanges = (orig, amend, addedTag = 'text-danger', removedTag = 'struck') => {
    let diff = Diff.diffWords(orig, amend);
    let out = [];
    // window.console.log(diff);
    diff.forEach((part) => {

        // window.console.log(this.tags.changeStart, 'hd');
        let w = '';
        if (part.added) {
            //we are on the first character in the changeset
            //so add the starting tag
            w += "<span class='" + addedTag + "'>";
        }

        if (part.removed) {
            w += "<span class='" + removedTag + "'>";
        }

        //add the actual character
        w += part.value;
        // window.console.log(me.splitNewText[i]);

        if (part.added || part.removed) {
            //we are at the end of the changes
            //so add the closing tag.
            w += '</span>'
        }

        //push it into the list that we will later join
        out.push(w);

    });

    return _.join(out, "");
};


module.exports.findMaxSize = (orig, amend) => {
    return (orig.length > amend.length) ? orig.length : amend.length;
};


module.exports.findChangeStart = (searchIndex, orig, amend) => {
    let maxIdx = this.findMaxSize(orig, amend);
    for (searchIndex; searchIndex <= maxIdx; searchIndex++) {
        if (orig[searchIndex] !== amend[searchIndex]) {
            return searchIndex;
        }
    }
    // return null;
};

/**
 * Receives two strings and returns an object
 *      { startIndex : x, stopIndex : y}
 * with the indexes of the first and last word in the change.
 *
 * @param oldText
 * @param newText
 * @param searchStartIndex
 * @returns {boolean|{stopIndex: *, startIndex: undefined}}
 */
module.exports.checkChanges = (oldText, newText, searchStartIndex = 0) => {
    //any changes
    let a = oldText.search(newText);
    if (a > -1) return false;

    let startIndex = this.findChangeStart(searchStartIndex, oldText, newText);

    let out = {
        startIndex: startIndex,
        //default all the way to end
        stopIndex: newText.length
    };

    for (let i = startIndex; i <= newText.length; i++) {
        let b = newText.length - i;
        let c = _.join(_.takeRight(newText, b), '');

        if (oldText.search(c) > -1) {
            //we are back to the original
            //so the index of the last change is the character
            //immediately preceding this one.
            out.stopIndex = i - 1;
            return out;
        }
    }
    return out;

};


/**
 * Used on resolution text where all text is htm
 *
 * @param oldHtml
 * @param newHtml
 * @returns {*}
 */
module.exports.getTaggedChangesOfHtml = (oldHtml, newHtml) => {
    // diffHtml.innerHTML = HtmlDiff.execute(oldHtml, newHtml);
    // return diffHtml.innerHTML;
}


//     }
// }

//
// /**
//  * Returns the index of the last non-equal word.
//  * Thus the closing tag goes between the index this returns
//  * and the next item in the list.
//  * @param searchStart
//  * @param orig
//  * @param amend
//  * @returns {*[]|*}
//  */
// function findChangeEnd(searchStart, orig, amend) {
//     let idx = searchStart;
//     let otl = orig.length;
//     let t = _.clone(orig);
//     for (idx; idx <= otl; idx++) {
//         t.splice(idx, 0, amend[idx]);
//         if (_.isEqual(t, amend)) {
//             return idx;
//             //if mere addition, returns the start index
//         }
//     }
//     return [t, amend];
// }
//
// function isInsertion(originalBag, amendmentBag) {
//     let changeStart = findChangeStart(0, originalBag, amendmentBag);
//     if (_.isUndefined(changeStart)) return false;
//     let changeEnd = findChangeEnd(changeStart, originalBag, amendmentBag);
//     if (_.isUndefined(changeEnd)) return false;
//
//     return {
//         type: 'insert',
//         start: changeStart,
//         end: changeEnd
//     }
// }
