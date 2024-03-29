/**
 * Created by adam on 7/7/17.
 */

const _ = window._ = require('lodash');
// const Vue = require( 'vue' );

import FileImportColumnStore from "../../../models/FileImportColumnStore";

import PoolMember from '../../../models/PoolMember';
import {
    decomposeFile,
    doesBrowserSupportFileUpload,
    doesFirstRowContainTitles
} from '../../../utilities/fileImport.utilities';


/**
 * Defines the properties of a file which contains the names of people
 * who may be candidates for an office.
 *
 * @type {[{standardTitle: string, regex: RegExp, commonData: string[], name: string}, {standardTitle: string, regex: RegExp, commonData: string[], name: string}, {standardTitle: string, regex: RegExp, commonData: string[], name: string}, {standardTitle: string, regex: RegExp, commonData: string[], name: string}]}
 */
const candidateColumns = [
    {
        name: 'firstName',
        standardTitle: 'First name',
        regex: new RegExp('first', 'gi'),

        // commonData is a list of the most common first names for candidates born between 1990-2000
        // It is used to scan a column and make a guess at which contains first names
        // dev This is a carry-over from the gradeomatic, names should be updated
        commonData: [
            'Michael', 'Carlos', 'Christopher', 'Matthew',
            'Maria', 'Joshua', 'Jacob', 'Nicholas',
            'Jessica', 'Jose', 'Ashley', 'Emily',
            'Sarah', 'Samantha', 'Amanda'
        ],
        // //Whether stored inside info
        // isInfoProp : false,
    },

    {
        name: 'lastName',
        standardTitle: 'Last name',
        regex: new RegExp('last', 'gi'),
        commonData: ['Smith', 'Lee', 'Gonzales']
    },

    {
        name: 'department',
        standardTitle: 'Department',
        regex: new RegExp('department', 'gi'),
        commonData: ['Psychology', 'sociology']
    },

    {
        name: 'link',
        standardTitle: 'Link',
        regex: new RegExp('link|url', 'gi'),
        commonData: ['https', 'http', 'www', '.com', '.edu']
    }

];

const actions = {

    /**
     * Creates a pool of potential candidates for an office from
     * the provided file which contains candidate names, etc (as defined in
     * candidateFileImporter.candidateColumns.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param file
     * @returns {Promise<unknown>}
     */
    createPoolFromFile({dispatch, commit, getters}, file) {
        let motion = getters.getActiveMotion;

        return new Promise(((resolve, reject) => {
            dispatch('readPeopleFromFile', file).then((people) => {
                window.console.log('people', people);
                //Will have a list of people objects
                _.forEach(people, (p) => {
                    p.motion_id = motion.id;
                    dispatch('createPerson', p).then((p2) => {
                        dispatch('addPersonToPool', {person: p2, motionId: motion.id})
                            .then(() => {
                                //dev VOT-169 shouldn't this be after the loop?
                                return resolve();
                            });
                    });
                });

            });

        }));
    },


    /**
     * Utility action for reading the file listing people.
     *
     * Returns a list of people objects
     *
     * @param state
     * @param dispatch
     * @param commit
     * @param getters
     * @param inputFile
     * @returns {Promise<unknown>}
     */
    readPeopleFromFile: ({state, dispatch, commit, getters}, inputFile) => {
        return new Promise((resolve, reject) => {
            //todo Temporarily commented out the promise while working on this since the below log gets called twice
            //todo The doubling of candidates on read happens because this action gets called twice. So in looking for the cause, don't focus here.... Are you listening Adam?

            window.console.log('candidateFileImporter', 'importCandidatesFromFile called', 'inputFile', inputFile);

            if (!doesBrowserSupportFileUpload()) {
                alert('The file upload function is not fully supported in this browser!');
                return;
            }

            let reader = new FileReader();
            let people = [];
            /**
             * Run the processing
             * From docs
             * The FileReader.onload property contains an event handler
             * executed when the load event is fired, when content read
             * with readAsArrayBuffer, readAsBinaryString, readAsDataURL
             * or readAsText is available.
             * https://developer.mozilla.org/en-US/docs/Web/API/FileReader/onload
             *
             * @param event
             */
            reader.onload = (event) => {
                let columnStore = new FileImportColumnStore(candidateColumns)

                //holds the candidates extracted from the file
                let {contents, rows} = decomposeFile(event);
                //rename for compatibility while refactoring
                let candidates = contents;

                /*
                At this point we have a nice clean representation
                of the input file.

                We now need to remove any non-data rows. But before
                we do that, we need to figure out what data is contained
                in each column.
                 */
                // analyze the file and look for column headers
                let firstLine = candidates[0];

                // window.console.log('first row titles', firstLine, doesFirstRowContainTitles(firstLine, ['name', 'department', 'url']));

                if (doesFirstRowContainTitles(firstLine, columnStore.standardTitles)) {
                    columnStore.setColumnIndexesFromTitles(firstLine);
                    // remove the header line as we don't need it any longer
                    rows.splice(0, 1);
                    candidates.splice(0, 1);
                } else {
                    columnStore.setColumnIndexesFromContents(candidates);
                }

                /* Test point: The data should be in candidates and columns should have correct order values */
                window.console.log('candidateFileImporter---initialRead TP', 'candidates', candidates, 'columns', columnStore);


                /*
                Now that everything is processed, we can send the candidate
                to storage and the server
                 For bug fixing, here are the values in the standard file
                    // let ident = candidate[ 0 ];
                    // let last = candidate[ 1 ];
                    // let first = candidate[ 2 ];
                    // let email = candidate[ 3 ];
                 */
                _.forEach(candidates, (candidate) => {
                    // window.console.log( 'candidateFileImporter', 'candidate', 249, candidate );
                    // let ident = candidate[columns.idCol];
                    let last = candidate[columnStore.lastName];
                    let first = candidate[columnStore.firstName];

                    // dev VOT-169
                    let dept = candidate[columnStore.department];
                    let link = candidate[columnStore.link]
                    let info = {
                        department: dept,
                        link: link
                    };

                    //create a candidate object
                    let s = new PoolMember({
                        last_name: last,
                        first_name: first,
                        info: info
                    });
                    //Push the candidate into local storage
                    people.push(s);
                });

                window.console.log('gonna pass back', people);
                return resolve(people);
            };

            reader.onerror = function (inputFile) {
                let errorText = 'Unable to read file'; //+ inputFile.fileName;
                alert(errorText);
                // reject( Error( errorText ) );
                throw Error(errorText);
            };

            return reader.readAsText(inputFile);
        });
    },

};



export {actions as default};
export {candidateColumns};



//actions
//     readPeopleFromFileOLD: ({state, dispatch, commit, getters}, inputFile) => {
//         return new Promise((resolve, reject) => {
//             //todo Temporarily commented out the promise while working on this since the below log gets called twice
//             //todo The doubling of candidates on read happens because this action gets called twice. So in looking for the cause, don't focus here.... Are you listening Adam?
//
//             window.console.log('candidateFileImporter', 'importCandidatesFromFile called', 'inputFile', inputFile);
//
//             if (!doesBrowserSupportFileUpload()) {
//                 alert('The file upload function is not fully supported in this browser!');
//                 return;
//             }
//
//             var reader = new FileReader();
//             let people = [];
//             /**
//              * Run the processing
//              * From docs
//              * The FileReader.onload property contains an event handler
//              * executed when the load event is fired, when content read
//              * with readAsArrayBuffer, readAsBinaryString, readAsDataURL
//              * or readAsText is available.
//              * https://developer.mozilla.org/en-US/docs/Web/API/FileReader/onload
//              *
//              * @param event
//              */
//             reader.onload = (event) => {
//                 let columnStore = new FileImportColumnStore(candidateColumns)
//                 // reset columns. prevents bugs if two files with different orderings are imported.
//                 let columns = {emailCol: -1, idCol: -1, firstNameCol: -1, lastNameCol: -1};
//
//                 let expectedTitleStrings = ['name', 'department', 'url'];
//
//                 //holds the candidates extracted from the file
//                 let {contents, rows} = decomposeFile(event);
//                 //rename for compatibility while refactoring
//                 let candidates = contents;
//
//                 // let candidates = f.contents;
// // let rows = f.rows;;
// //                 window.console.log('candidates', candidates,  'rows', rows);
//                 // /* We start by reading and decomposing the file */
//                 // // convert line endings
//                 // var rows = event.target.result.toString().replace(/[\r\n]+/g, "\n").split("\n");
//                 //
//                 // // break each row into its elements, pushing them into candidates
//                 // for (var i = 0; i < rows.length; i++) {
//                 //     candidates[i] = rows[i].toString().split(separatorChar);
//                 // }
//                 //
//                 // /*
//                 // Now we can process the read data
//                 // */
//                 // // remove any resulting lines with 1 or fewer elements
//                 // for (i = candidates.length - 1; i >= 0; i--) {
//                 //     // since this looks for rows with 2 or more consecutive commas, rows that import with a few empty columns
//                 //     // at the beginning (eg:  [,,,data,data,data] ) will be spliced. IT should remove lines with only commas.
//                 //     if (candidates[i].length <= 1 || (rows[i].search(/,,+/) >= 0)) {
//                 //         candidates.splice(i, 1);
//                 //         rows.splice(i, 1);
//                 //     }
//                 // }
//
//                 /*
//                 At this point we have a nice clean representation
//                 of the input file.
//
//                 We now need to remove any non-data rows. But before
//                 we do that, we need to figure out what data is contained
//                 in each column.
//                  */
//                 // analyze the file and look for column headers
//                 var firstLine = candidates[0];
//                 var startRow = 0;
//
//                 window.console.log('first row titles', firstLine, doesFirstRowContainTitles(firstLine, ['name', 'department', 'url']));
//
//                 if (doesFirstRowContainTitles(firstLine, expectedTitleStrings)) {
//                     guessColumnDataByTitles(firstLine, columns);
//                     // remove the header line as we don't need it any longer
//                     rows.splice(0, 1);
//                     candidates.splice(0, 1);
//                 } else {
//                     guessColumnDataByContent(candidates, columns);
//                 }
//
//                 /* Test point: The data should be in candidates and columns should have correct order values */
//                 window.console.log('candidateFileImporter---initialRead TP', 'candidates', candidates, 'columns', columns);
//
//
//                 /*
//                 Now that everything is processed, we can send the candidate
//                 to storage and the server
//                  For bug fixing, here are the values in the standard file
//                     // let ident = candidate[ 0 ];
//                     // let last = candidate[ 1 ];
//                     // let first = candidate[ 2 ];
//                     // let email = candidate[ 3 ];
//                  */
//                 _.forEach(candidates, (candidate) => {
//                     // window.console.log( 'candidateFileImporter', 'candidate', 249, candidate );
//                     // let ident = candidate[columns.idCol];
//                     let last = candidate[columns.lastNameCol];
//                     let first = candidate[columns.firstNameCol];
//
//                     // dev VOT-169
//                     let dept = candidate[columns.deptCol];
//                     let link = candidate[columns.linkCol]
//                     let info = {
//                         department: dept,
//                         link: link
//                     };
//                     // let email = candidate[columns.emailCol];
//
//
//                     //create a candidate object
//                     let s = new PoolMember({
//                         last_name: last,
//                         first_name: first,
//                         //dev VOT-169
//                         info: info
//                         // candidateIdentifier: ident,
//                         // email: email
//                     });
//                     //Push the candidate into local storage and create
//                     //a new candidate on the server
//                     people.push(s);
//                     // dispatch(aTypes.handleNewcandidateStorageAndAssociation, s);
//                 });
//
//
//                 window.console.log('gonna pass back', people);
//                 return resolve(people);
//
//
//             };
//
//             reader.onerror = function (inputFile) {
//                 let errorText = 'Unable to read file'; //+ inputFile.fileName;
//                 alert(errorText);
//                 // reject( Error( errorText ) );
//                 throw Error(errorText);
//             };
//
//             return reader.readAsText(inputFile);
//             //     reader.readAsText(inputFile);
//         });
//     }




//
// const filterHeaderRows = (candidates) => {
//
//     // remove any resulting lines with 1 or fewer elements
//     for (let i = candidates.length - 1; i >= 0; i--) {
//         // since this looks for rows with 2 or more consecutive commas, rows that import with a few empty columns
//         // at the beginning (eg:  [,,,data,data,data] ) will be spliced. IT should remove lines with only commas.
//         if (candidates[i].length <= 1 || (candidates[i].search(/,,+/) >= 0)) {
//             candidates.splice(i, 1);
//         }
//     }
//     return candidates;
// };


// commonNames[] is a list of the most common first names for candidates born between 1990-2000
// It is used to scan a column and make a guess at which contains first names
// const commonNames = [
//     'Michael', 'Carlos', 'Christopher', 'Matthew',
//     'Maria', 'Joshua', 'Jacob', 'Nicholas',
//     'Jessica', 'Jose', 'Ashley', 'Emily',
//     'Sarah', 'Samantha', 'Amanda'
// ];

/**
 * [ separatorChar] defines the character that will be used to divide lines into fields
 * default: comma
 */
// const separatorChar = ',';
//
// /**
//  * check that the browser isn't ancient
//  * @returns {boolean}
//  */
// const browserSupportFileUpload = () => {
//     var isCompatible = false;
//     if (window.File && window.FileReader && window.FileList && window.Blob) {
//         isCompatible = true;
//     }
//     return isCompatible;
// };

// /**
//  * determines if the first row contains column headers that describe the column's content
//  * @deprecated
//  * @param firstLine
//  * @returns {boolean}
//  */
// const firstRowContainsTitles = function (firstLine) {
//     var result = false;
//     if (typeof firstLine != 'undefined') {
//         for (var i = 0; i < firstLine.length; i++) {
//             if (firstLine[i].search(/mail/i) >= 0 || firstLine[i].search(/name/i) >= 0) {
//                 result = true;
//             }
//             if (firstLine[i].search(/@/) >= 0) {
//                 result = false;
//             }
//         }
//     }
//     return result;
// };
//
// /**
//  * examine column titles to pick likely ordering
//  * @param titles
//  */
// const guessColumnDataByTitles = function (titles, cols = {
//     emailCol: -1,
//     idCol: -1,
//     firstNameCol: -1,
//     lastNameCol: -1,
//     //dev VOT-169
//     deptCol: -1,
//     linkCol: -1
// }) {
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
//
// /**
//  * Examine table data to pick out column ordering
//  */
// const guessColumnDataByContent = function (candidates, columns = {
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



