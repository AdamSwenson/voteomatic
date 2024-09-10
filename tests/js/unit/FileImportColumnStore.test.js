let _ = require('lodash');
// import {amendmentType, diffTagText, getChangedText} from "../../../../resources/js/utilities/amendment.utilities";
import FileImportColumnStore from "../../../resources/js/models/FileImportColumnStore";

describe('FileImportColumnStore ', () => {
    const candidateColumns = [
        {
            name: 'firstName',
            regex: new RegExp('first', 'gi'),
            commonData: [
                'Michael', 'Carlos', 'Christopher', 'Matthew',
                'Maria', 'Joshua', 'Jacob', 'Nicholas',
                'Jessica', 'Jose', 'Ashley', 'Emily',
                'Sarah', 'Samantha', 'Amanda'
            ],
            expectedIndex: 0
        },

        {
            name: 'lastName',
            regex: new RegExp('last', 'gi'),
            commonData: ['Smith', 'Lee', 'Gonzales'],
            expectedIndex: 2,
        },

        {
            name: 'department',
            regex: new RegExp('department', 'gi'),
            commonData: ['Psychology', 'sociology'],
            expectedIndex: 1
        },

        {
            name: 'link',
            regex: new RegExp('link|url', 'gi'),
            commonData: ['https', 'http', 'www', '.com', '.edu'],
            expectedIndex: 3
        }

    ];
    beforeEach(() => {

    });


    describe('setColumnIndexesFromTitles ', () => {


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
                if (c.name === 'link') {
                    //should still be in default state
                    expect(cs[c.name]).toBe(-1);
                } else {
                    expect(cs[c.name]).toBe(c.expectedIndex);
                }
            });
        });
    });


    describe('setColumnIndexesFromData ', () => {


        test('Happy path', () => {
            let cs = new FileImportColumnStore(candidateColumns);
            let data = [
                ['Adam', 'Philosophy', 'Smith', 'http://www.wag.com'],
                ['Waggleback', 'Psychology', 'Barkson',  'http://www.wag.com'],
                ['Carlos', 'Theater', 'Buck',  'http://www.wag.com']
            ];
            //call
            cs.setColumnIndexesFromContents(data);
            //check
            _.forEach(candidateColumns, (c) => {
                expect(cs[c.name]).toBe(c.expectedIndex);
            });
        });

        test('Missing columns', () => {
            let cs = new FileImportColumnStore(candidateColumns);
            let data = [
                ['Adam', 'Philosophy', 'Smith', ],
                ['Waggleback', 'Psychology', 'Barkson',],
                ['Carlos', 'Theater', 'Buck',  ]
            ];
            cs.setColumnIndexesFromContents(data);
            _.forEach(candidateColumns, (c) => {
                if (c.name === 'link') {
                    //should still be in default state
                    expect(cs[c.name]).toBe(-1);
                } else {
                    expect(cs[c.name]).toBe(c.expectedIndex);
                }
            });
        });
    });
});
