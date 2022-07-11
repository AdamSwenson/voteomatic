import {makeRegexFromList} from "../utilities/regex.utilities";

/**
 * This will handle determining things like the column order
 * in an imported csv file.
 */
export default class FileImportColumnStore {

    /**
     * Expects a list of objects defining the name of the property
     * and a regex for finding that column in the titles.
     * Optionally, it may contain a list of common values found in the fields
     * for that column
     * For example:
     *      [
     *          {
     *              name: 'firstName',
     *              regex: new RegExp('first', 'gi'),
     *               commonData : ['Michael', 'Carlos', 'Christopher', 'Matthew',
     *               'Maria', 'Jessica', 'Jose', ]
     *           },
     *           {
     *              name: 'lastName',
     *              regex: new RegExp('last', 'gi'),
     *              commonData : []
     *            },
     *           ....
     *     ]
     *
     * @param columns
     */
    constructor(columns) {
        this.columns = columns;
        let me = this;

        //Use the list of columns to create properties on the object
        //with the name of each column.
        _.forEach(this.columns, (p) => {
            me[p.name] = -1;
            //Create a regex from commonData if provided
            p.dataRegex = me.makeDataRegexForColumn(p);
        });
    }

    makeDataRegexForColumn(columnDefinition) {
        if (!_.isUndefined(columnDefinition.commonData) && columnDefinition.commonData.length > 0) {
            return makeRegexFromList(columnDefinition.commonData);
        }
    }

    get standardTitles() {
        let out = [];
        _.forEach(this.columns, (c) => {
            out.push(c.standardTitle);
        })
        return out;
    }

    /**
     * Uses the regex defined in this.columns to
     * determine the property name corresponding to the
     * provided string (i.e., the column title from the file).
     * @param title
     * @returns {*}
     */
    findColumnNameForTitle(title) {
        let out = _.filter(this.columns, (c) => {
            return title.search(c.regex) >= 0
        });
        if (out.length > 0) return out[0].name;
    }

    /**
     * Sets the indexes of each column on the object's properties
     * based on the column titles in the file.
     *
     * So, if the titles are ['first name', 'department', 'last name', 'link']
     * we will have:
     *      this.firstName = 0
     *      this.department = 1
     *      this.lastName = 2
     *      this.link = 3
     *
     * dev What should this do if there is no property for a provided title?
     *
     * @param titles
     */
    setColumnIndexesFromTitles(titles) {
        let numColumns = titles.length;

        for (let i = 0; i < numColumns; i++) {
            let colName = this.findColumnNameForTitle(titles[i]);
            if (!_.isUndefined(colName)) {
                this[colName] = i;
            }
        }
    }

    findColumnNameForData(concatenatedDataString) {
        let out = _.filter(this.columns, (c) => {

            return concatenatedDataString.search(c.dataRegex) >= 0
        });
        if (out.length > 0) return out[0].name;
    }

    /**
     * Receives an array of arrays. Each item is
     * a list of the cells in a row. E.g.,
     *      [
     *      0: ['Adam', 'Smith', 'Philosophy']
     *      1: ['Waggleback', 'Barkson', 'CS']
     *      2: ['Major', 'Buck', 'Theater']
     *      ]
     * @param data
     */
    setColumnIndexesFromContents(data) {
        //count the items in the first row
        //NB, if the first row has less fields than others, this will screw things up
        let numColumns = data[0].length;

        for (let i = 0; i < numColumns; i++) {
            //concatenate all the data in the column
            let colData = "";
            _.forEach(data, (d) => {
                colData += d[i];
                colData += " ";
            });

            let colName = this.findColumnNameForData(colData);
            if (!_.isUndefined(colName)) {
                this[colName] = i;
            }
        }


    }


    // /** Examine table data to pick out column ordering*/
    // guessColumnDataByContent(candidates, columns = {
    //     emailCol: -1,
    //     idCol: -1,
    //     firstNameCol: -1,
    //     lastNameCol: -1
    // }) {
    //     window.console.log('candidateFileImporter', 'guessColumnDataByContent', 90, candidates);
    //     var startCol = 0;
    //     var startRow = 0;
    //
    //     var numColumns = candidates[0].length;
    //     var foundColumns = [];
    //
    //     for (var i = startCol; i < numColumns; i++) {
    //         if (candidates[0][i].search(/@/) >= 0) {
    //             // look for @, that's the email
    //             columns.emailCol = i;
    //             foundColumns.push(i);
    //         } else if (candidates[0][i].search(/[0-9]{3}/) >= 0) {
    //             // look for 3 digits in a row, that's the candidateId
    //             columns.idCol = i;
    //             foundColumns.push(i);
    //         } else if (candidates[0][i] == '') {
    //             // add any empty columns to the blacklist so they are skipped later
    //             foundColumns.push(i);
    //         }
    //     }
    //
    //     for (i = startCol; i < numColumns; i++) {
    //         // skip any columns which have already been flagged as email or candidate ID
    //         if (foundColumns.indexOf(i) > -1) {
    //             continue;
    //         }
    //         for (var j = startRow; j < candidates.length; j++) {
    //             // any column with 3 more letters is set as last name. Next column found with letters is first name
    //             if (candidates[j][i].search(/.{3,}/) > -1) {
    //                 if (columns.lastNameCol == -1)
    //                     columns.lastNameCol = i;
    //                 else if (columns.lastNameCol != i) {
    //                     columns.firstNameCol = i;
    //                 }
    //             }
    //             // look for common names and set firstNameCol if any are found
    //             if (candidates[j][i].search(commonNames)) {
    //                 columns.firstNameCol = i;
    //                 if (columns.lastNameCol == columns.firstNameCol) {
    //                     columns.lastNameCol = -1;
    //                 }
    //                 foundColumns.push(i);
    //                 break;
    //             }
    //         }
    //     }
    //     return columns
    // }

};
