const _ = require("lodash");


/**
 * Creates a regular expression object for searching for the
 * strings in the list using the given operator
 * @param stringList
 * @param flagList
 */
module.exports.makeRegexFromList = (stringList, flagList = ['i', 'g'], operator = '|') => {

    let exp = '';
    for (let i = 0; i < stringList.length; i++) {
        exp += stringList[i];
        if (i < stringList.length - 1) {
            exp += operator;
        }
    }

    let flags = '';
    _.forEach(flagList, (f) => {
        flags += f;
    });

    return new RegExp(exp, flags);

};
