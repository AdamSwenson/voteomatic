/**
 * Created by adam on 6/9/22.
 */

import {
    decomposeFile,
    doesBrowserSupportFileUpload,
    doesFirstRowContainTitles
} from "../../../utilities/fileImport.utilities";
import Office from "../../../models/Office";


const _ = window._ = require('lodash');
// const Vue = require( 'vue' );

import PoolMember from '../../../models/PoolMember';
import FileImportColumnStore from "../../../models/FileImportColumnStore";
import * as routes from "../../../routes";
import {idify} from "../../../utilities/object.utilities";
import Motion from "../../../models/Motion";

/**
 * Defines the properties of a file which describe the offices
 * for an election
 *
 * @type {[{standardTitle: string, regex: RegExp, commonData: string[], name: string}, {standardTitle: string, regex: RegExp, commonData: number[], name: string}, {standardTitle: string, regex: RegExp, commonData: *[], name: string}]}
 */
const officeColumns = [

    {
        name: 'officeName',
        standardTitle: 'Office name',
        regex: new RegExp('office|name', 'gi'),
        commonData: ['President', 'Secretary', 'Senator']
    },

    {
        name: 'maxWinners',
        standardTitle: 'Max winners',
        regex: new RegExp('max|winners', 'gi'),
        commonData: [1]
    },

    {
        name: 'description',
        standardTitle: 'Description',
        regex: new RegExp('description', 'gi'),
        commonData: []
    }

];

const actions = {


    /**
     * Handles all steps for creating offices in an election from an
     * uploaded file.
     *
     * This is the action which should be called
     *
     * @param state
     * @param dispatch
     * @param commit
     * @param getters
     * @param inputFile
     * @returns {Promise<unknown>}
     */
    createOfficesFromFile: ({state, dispatch, commit, getters}, inputFile) => {
        return new Promise((resolve, reject) => {
            window.console.log('officeFileImporter', 'createOfficesFromFile called', 'inputFile', inputFile);
            let url = routes.election.resource.office();

            dispatch('readOfficesFromFile', inputFile).then((offices) => {
                // window.console.log('offices', offices);
                _.forEach(offices, (office) => {

                    Vue.axios.post(url, office)
                        .then((response) => {
                            let motion = new Office(response.data);
                          //  window.console.log('adding office to store', motion);
                             commit('addMotionToStore', motion);

                        })
                        .catch(function (error) {
                            //NB, this will catch all errors, including ones not having to do
                            //with the server request. If things are going weird with no obvious error
                            //displays, this may be why. (Guess why the console log call is here...)
                            window.console.log(error);
                            // error handling
                            if (error.response) {
                                dispatch('showServerProvidedMessage', error.response.data);
                            }
                        });
                });
                return resolve();

            });

        });


    },


    /**
     * Utility action for reading the offices-defining file in, determining and organizing the contents,
     * before passing them back.
     *
     * This is unlikely to be called from anywhere other than the createOffices action
     *
     * @param state
     * @param dispatch
     * @param commit
     * @param getters
     * @param inputFile
     * @returns {Promise<unknown>}
     */
    readOfficesFromFile: ({state, dispatch, commit, getters}, inputFile) => {
        return new Promise((resolve, reject) => {
            window.console.log('officeFileImporter', 'createOfficesFromFile called', 'inputFile', inputFile);

            if (!doesBrowserSupportFileUpload()) {
                alert('The file upload function is not fully supported in this browser!');
                return;
            }

            let reader = new FileReader();

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
                let columnStore = new FileImportColumnStore(officeColumns)

                //holds the contents extracted from the file
                let {contents, rows} = decomposeFile(event);

                /*
                At this point we have a nice clean representation
                of the input file.

                We now need to remove any non-data rows. But before
                we do that, we need to figure out what data is contained
                in each column.
                 */
                // analyze the file and look for column headers
                let firstLine = contents[0];

                // window.console.log('first row titles', firstLine, doesFirstRowContainTitles(firstLine, ['name', 'department', 'url']));

                if (doesFirstRowContainTitles(firstLine, columnStore.standardTitles)) {
                    columnStore.setColumnIndexesFromTitles(firstLine);
                    // remove the header line as we don't need it any longer
                    rows.splice(0, 1);
                    contents.splice(0, 1);
                } else {
                    columnStore.setColumnIndexesFromContents(contents);
                }

                /* Test point: The data should be in contents and columns should have correct order values */
                window.console.log('officeFileImporter---initialRead TP', 'contents', contents, 'columns', columnStore);

                let meeting = getters.getActiveMeeting;
                let url = routes.election.resource.office();

                let offices = [];

                /*
                Now that everything is processed, we can send the office
                to storage and the server
                */
                let idx = 0;
                _.forEach(contents, (c) => {
                    idx += 1;

                    let s = {
                        meetingId: idify(meeting),
                        //NB, an office is represented by a motion, hence we need to use
                        //the expected keys even though it seems odd in this context
                        content: c[columnStore.officeName],
                        description: c[columnStore.description],
                        max_winners: c[columnStore.maxWinners],
                        //Otherwise the controller will not send the office
                        //when we ask for all motions
                        seconded: true,
                        type: 'election'
                    };
                    offices.push(s);

                });

                // window.console.log('gonna pass back', offices);
                return resolve(offices);
            };

            reader.onerror = function (inputFile) {
                let errorText = 'Unable to read file'; //+ inputFile.fileName;
                alert(errorText);
                // reject( Error( errorText ) );
                throw Error(errorText);
            };

            return reader.readAsText(inputFile);
        });
    }

};

export {actions as default}


//
//
//     if (!doesBrowserSupportFileUpload()) {
//         alert('The file upload function is not fully supported in this browser!');
//         return;
//     }
//
//     let reader = new FileReader();
//
//     /**
//      * Run the processing
//      * From docs
//      * The FileReader.onload property contains an event handler
//      * executed when the load event is fired, when content read
//      * with readAsArrayBuffer, readAsBinaryString, readAsDataURL
//      * or readAsText is available.
//      * https://developer.mozilla.org/en-US/docs/Web/API/FileReader/onload
//      *
//      * @param event
//      */
//     reader.onload = (event) => {
//         let columnStore = new FileImportColumnStore(officeColumns)
//
//         //holds the contents extracted from the file
//         let {contents, rows} = decomposeFile(event);
//
//         /*
//         At this point we have a nice clean representation
//         of the input file.
//
//         We now need to remove any non-data rows. But before
//         we do that, we need to figure out what data is contained
//         in each column.
//          */
//         // analyze the file and look for column headers
//         let firstLine = contents[0];
//
//         // window.console.log('first row titles', firstLine, doesFirstRowContainTitles(firstLine, ['name', 'department', 'url']));
//
//         if (doesFirstRowContainTitles(firstLine, columnStore.standardTitles)) {
//             columnStore.setColumnIndexesFromTitles(firstLine);
//             // remove the header line as we don't need it any longer
//             rows.splice(0, 1);
//             contents.splice(0, 1);
//         } else {
//             columnStore.setColumnIndexesFromContents(contents);
//         }
//
//         /* Test point: The data should be in contents and columns should have correct order values */
//         window.console.log('officeFileImporter---initialRead TP', 'contents', contents, 'columns', columnStore);
//
//         let meeting = getters.getActiveMeeting;
//         let url = routes.election.resource.office();
//
//         /*
//         Now that everything is processed, we can send the office
//         to storage and the server
//         */
//         let idx=0;
//         _.forEach(contents, (c) => {
//             idx += 1;
//
//             let s = {
//                 meetingId: idify(meeting),
//                 //NB, an office is represented by a motion, hence we need to use
//                 //the expected keys even though it seems odd in this context
//                 content: c[columnStore.officeName],
//                 description: c[columnStore.description],
//                 max_winners: c[columnStore.maxWinners],
//                 //Otherwise the controller will not send the office
//                 //when we ask for all motions
//                 seconded: true,
//                 type: 'election'
//             };
//
//             // return new Promise(((resolve, reject) => {
//             window.console.log(url, s, idx);
//             Vue.axios.post(url, s)
//                 .then((response) => {
//                     let motion = new Office(response.data);
//
//                     //dev THIS IS THE CURRENT PROBLEM: NOT FIRING MUTATION
//                     commit('addMotionToStore', motion);
//                     window.console.log(idx, motion);
//                     if(idx === contents.length){
//                         return resolve();
//                     }
//
//
//                 }).catch(function (error) {
//                 // error handling
//                 if (error.response) {
//                     dispatch('showServerProvidedMessage', error.response.data);
//                 }
//             });
//             // }));
//
//
//         });
//
//         // window.console.log('gonna pass back', offices);
//         return resolve();
//     };
//
//     reader.onerror = function (inputFile) {
//         let errorText = 'Unable to read file'; //+ inputFile.fileName;
//         alert(errorText);
//         // reject( Error( errorText ) );
//         throw Error(errorText);
//     };
//
//     return reader.readAsText(inputFile);
// });
//
