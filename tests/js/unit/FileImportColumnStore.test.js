let _ = require('lodash');
// import {amendmentType, diffTagText, getChangedText} from "../../../../resources/js/utilities/amendment.utilities";
import FileImportColumnStore from "../../../resources/js/models/FileImportColumnStore";

describe('FileImportColumnStore ', () => {
    beforeEach(() => {

    });


    describe('setColumnIndexesFromTitles ', () => {
        const candidateColumns = [
            {
                name: 'firstName',
                regex: new RegExp('first', 'gi'),
                expectedIndex: 0
            },

            {
                name: 'lastName',
                regex: new RegExp('last', 'gi'),
                expectedIndex: 2,
            },

            {
                name: 'department',
                regex: new RegExp('department', 'gi'),
                expectedIndex: 1
            },

            {
                name: 'link',
                regex: new RegExp('link|url', 'gi'),
                expectedIndex: 3
            }

        ];

        test('Happy path', () => {
            let cs = new FileImportColumnStore(candidateColumns);
            let titles = ['first name', 'department', 'last name', 'link']
            cs.setColumnIndexesFromTitles(titles);
            _.forEach(candidateColumns, (c) => {
                expect(cs[c.name]).toBe(c.expectedIndex);

            });
        });

        test('Missing columns', () => {
            let cs = new FileImportColumnStore(candidateColumns);
            let titles = ['first name', 'department', 'last name']
            cs.setColumnIndexesFromTitles(titles);
            _.forEach(candidateColumns, (c) => {
                if(c.name === 'link'){
                    //should still be in default state
                    expect(cs[c.name]).toBe(-1);
                }else{
                    expect(cs[c.name]).toBe(c.expectedIndex);
                }
            });
        });
    });
});
