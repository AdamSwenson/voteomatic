let _ = require('lodash');
// import {amendmentType, diffTagText, getChangedText} from "../../../../resources/js/utilities/amendment.utilities";
import {doesFirstRowContainTitles} from "../../../../resources/js/utilities/fileImport.utilities";

describe('fileImport.utilities ', () => {
    beforeEach(() => {

    });
    //
    describe('doesFirstRowContainTitles matches expected string', () => {
        test('exact ', () => {
            let r = doesFirstRowContainTitles(['name',], ['name', 'url']);
            // let r = doesFirstRowContainTitles(['first name', 'last name',], ['name', 'url']);
            expect(r).toBeTruthy();
        });


        test('Case insensitive', () => {
            let r = doesFirstRowContainTitles(['First Name', 'Dog friend'], ['name', 'url']);
            expect(r).toBeTruthy();
        });

        test('Space insensitive', () => {
            let r = doesFirstRowContainTitles(['FirstName', 'DogFriend'], ['name', 'url']);
            expect(r).toBeTruthy();
        });

    });

    describe('doesFirstRowContainTitles matches bad string', () => {
        test('matches good and bad ', () => {
            let r = doesFirstRowContainTitles(['name@waggle',], ['name', 'url'], ['@']);
            expect(r).toBeFalsy();
        });


    });

    //
    // describe('setColumnIndexesFromTitles ', () => {
    //     const candidateColumns = [
    //         {
    //             name: 'firstName',
    //             regex: new RegExp('first', 'gi'),
    //             expectedIndex: 0
    //         },
    //
    //         {
    //             name: 'lastName',
    //             regex: new RegExp('last', 'gi'),
    //             expectedIndex: 2,
    //         },
    //
    //         {
    //             name: 'department',
    //             regex: new RegExp('department', 'gi'),
    //             expectedIndex: 1
    //         },
    //
    //         {
    //             name: 'link',
    //             regex: new RegExp('link|url', 'gi'),
    //             expectedIndex: 3
    //         }
    //
    //     ];
    //
    //     test('Happy path', () => {
    //         let cs = new FileImportColumnStore(candidateColumns);
    //         let titles = ['first name', 'department', 'last name', 'link']
    //         cs.setColumnIndexesFromTitles(titles);
    //         _.forEach(candidateColumns, (c) => {
    //             expect(cs[c.name]).toBe(c.expectedIndex);
    //
    //         });
    //     });
    //
    //     test('Missing columns', () => {
    //         let cs = new FileImportColumnStore(candidateColumns);
    //         let titles = ['first name', 'department', 'last name']
    //         cs.setColumnIndexesFromTitles(titles);
    //         _.forEach(candidateColumns, (c) => {
    //             if(c.name === 'link'){
    //                 //should still be in default state
    //                 expect(cs[c.name]).toBe(-1);
    //             }else{
    //                 expect(cs[c.name]).toBe(c.expectedIndex);
    //             }
    //         });
    //     });
    // });
});
