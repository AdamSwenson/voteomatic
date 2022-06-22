import {
    decomposeFile,
    doesBrowserSupportFileUpload,
    doesFirstRowContainTitles
} from "../../../utilities/fileImport.utilities";

/**
 * Created by adam on 6/9/22.
 */


const _ = window._ = require('lodash');
// const Vue = require( 'vue' );

import PoolMember from '../../../models/PoolMember';
import FileImportColumnStore from "../../../models/FileImportColumnStore";
import * as routes from "../../../routes";
import {idify} from "../../../utilities/object.utilities";
import Motion from "../../../models/Motion";


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

    createOfficesFromFile: ({state, dispatch, commit, getters}, inputFile) => {
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

                /*
                Now that everything is processed, we can send the office
                to storage and the server
                */
                _.forEach(contents, (c) => {
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

                    // return new Promise(((resolve, reject) => {

                    Vue.axios.post(url, s)
                        .then((response) => {
                            let motion = new Office(response.data);

                            commit('addMotionToStore', motion);


                        }).catch(function (error) {
                        // error handling
                        if (error.response) {
                            dispatch('showServerProvidedMessage', error.response.data);
                        }
                    });
                    // }));


                });

                // window.console.log('gonna pass back', offices);
                return resolve();
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


