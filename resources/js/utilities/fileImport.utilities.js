let _ = require('lodash');
import {makeRegexFromList} from "../utilities/regex.utilities";

// export class FileImportColumnStore {
//     /**
//      * Expects a list of objects defining the name of the property
//      * and a regex for finding that column in the titles.
//      * For example:
//      *      [
//      *          {
//      *              name: 'firstName',
//      *              regex: new RegExp('first', 'gi'),
//      *           },
//      *           {
//      *              name: 'lastName',
//      *              regex: new RegExp('last', 'gi'),
//      *            },
//      *           ....
//      *     ]
//      *
//      * @param columns
//      */
//     constructor(columns) {
//         this.columns = columns;
//         let me = this;
//
//         //Use the list of columns to create properties on the object
//         //with the name of each column.
//         _.forEach(this.columns, (p) => {
//             me[p.name] = -1;
//         });
//     }
//
//     /**
//      * Uses the regex defined in this.columns to
//      * determine the property name corresponding to the
//      * provided string (i.e., the column title from the file).
//      * @param title
//      * @returns {*}
//      */
//     findColumnNameForTitle(title) {
//         let out = _.filter(this.columns, (c) => {
//             return title.search(c.regex) >= 0
//         });
//         if (out.length > 0) return out[0].name;
//     }
//
//     /**
//      * Sets the indexes of each column on the object's properties
//      * based on the column titles in the file.
//      *
//      * So, if the titles are ['first name', 'department', 'last name', 'link']
//      * we will have:
//      *      this.firstName = 0
//      *      this.department = 1
//      *      this.lastName = 2
//      *      this.link = 3
//      *
//      * dev What should this do if there is no property for a provided title?
//      *
//      * @param titles
//      */
//     setColumnIndexesFromTitles(titles) {
//         let numColumns = titles.length;
//
//         for (let i = 0; i < numColumns; i++) {
//             let colName = this.findColumnNameForTitle(titles[i]);
//             if (!_.isUndefined(colName)) {
//                 this[colName] = i;
//             }
//         }
//     }
//
// };


/**
 * Decomposes the file loaded in the event handler and returns
 * the contents
 * @param event
 * @param separatorChar Defines the character that will be used to divide lines into fields
 *
 */
const decomposeFile = (event, separatorChar = ',') => {
    //holds the contents extracted from the file
    let contents = [];

    /* We start by reading and decomposing the file */
    // convert line endings
    let rows = event.target.result.toString().replace(/[\r\n]+/g, "\n").split("\n");

    // break each row into its elements, pushing them into contents
    for (let i = 0; i < rows.length; i++) {
        contents[i] = rows[i].toString().split(separatorChar);
    }

    /* Now we can process the read data */
    // remove any resulting lines with 1 or fewer elements
    for (let i = contents.length - 1; i >= 0; i--) {
        // since this looks for rows with 2 or more consecutive commas, rows that import with a few empty columns
        // at the beginning (eg:  [,,,data,data,data] ) will be spliced. IT should remove lines with only commas.
        if (contents[i].length <= 1 || (rows[i].search(/,,+/) >= 0)) {
            contents.splice(i, 1);
            rows.splice(i, 1);
        }
    }

    return {contents: contents, rows: rows};

};


/**
 * check that the browser isn't ancient
 * @returns {boolean}
 */
const doesBrowserSupportFileUpload = () => {
    let isCompatible = false;
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        isCompatible = true;
    }
    window.console.log('browser supports file upload', isCompatible);
    return isCompatible;
};



/**
 * determines if the first row contains column headers that describe the column's content
 * @param firstLine List of strings to examine
 * @param expectedStrings List of strings which should return true if any present
 * @param badStrings List of strings which will never appear in the first row if there are column headers in it
 * @returns {boolean}
 */
const doesFirstRowContainTitles = (firstLine, expectedStrings, badStrings = []) => {
    let result = false;

    if (_.isUndefined(firstLine)) return result;

    let searchRegex = makeRegexFromList(expectedStrings, ['g', 'i']);
    let excludeRegex = makeRegexFromList(badStrings, ['g', 'i']);

    //Combine the list into one single string so
    //we can check it in one pass
    let firstLineStrings = '';
    _.forEach(firstLine, (s) => {
        firstLineStrings += s;
        firstLineStrings += ' ';
    });

    //If any of the excluded strings are present, the row is no good.
    if (badStrings.length > 0 && firstLineStrings.search(excludeRegex) >= 0) {
        return false;
    }

    if (firstLineStrings.search(searchRegex) >= 0) {
        //We've found something
        return true;
    }

    return false;

};

//
// /**
//  * examine column titles to pick likely ordering
//  * @param titles
//  */
// module.exports.guessColumnDataByTitles = function (titles, columnStore) {
//     var numColumns = titles.length;
//
//     for (var i = 0; i < numColumns; i++) {
//         if (titles[i].search(/mail/i) >= 0) {
//             cols.emailCol = i;
//         } else if (titles[i].search(/id/) >= 0) {
//             cols.idCol = i;
//         } else if (titles[i].search(/first/) >= 0) {
//             cols.firstNameCol = i;
//         } else if (titles[i].search(/last/) >= 0) {
//             cols.lastNameCol = i;
//         }
//
//         //dev VOT-169
//         else if (titles[i].search(/department/) >= 0) {
//             cols.deptCol = i;
//         } else if (titles[i].search(/link/) >= 0) {
//             cols.linkCol = i;
//         } else {
//             console.log('column not found: "' + titles[i] + '"');
//         }
//     }
//
//     // return cols;
// };

export {
    decomposeFile,
    doesFirstRowContainTitles,
    doesBrowserSupportFileUpload
}
