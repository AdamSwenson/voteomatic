// module.exports = {
//
//     methods: {
//

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
