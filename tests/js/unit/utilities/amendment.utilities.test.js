let _ = require('lodash');
import {amendmentType, diffTagText, getChangedText} from "../../../../resources/js/utilities/amendment.utilities";

describe('amendment.utilities ', () => {
    beforeEach(() => {

    });

    describe('diffTagText ', () => {
        let og = '<p>The good dog smells.</p>';

        beforeEach(() => {

        });

        test('insert ', () => {
            // let og = '<p>The good dog smells.</p>';
            // let amend = '<p>The good dog smells nice.</p>'
            // let expected = '<p>The good dog smells<ins class="diffins">&nbsp;nice</ins>.</p>';
            let amend = '<p>The good dog smells nice.</p>'
            let expected = '<p>The good dog smells<ins class="diffins">&nbsp;nice</ins>.</p>';

            expect(diffTagText(og, amend)).toBe(expected);
        });
        test('strike ', () => {
            let amend = '<p>The good dog.</p>'
            let expected = '<p>The good dog<del class="diffdel">&nbsp;smells</del>.</p>';

            expect(diffTagText(og, amend)).toBe(expected);
        });
    });

    describe('amendmentType ', () => {
        let og = '<p>The good dog smells.</p>';
        let tagged;
        beforeEach(() => {
        });

        test('insert ', () => {
            let amend = '<p>The good dog smells nice.</p>'
            let expected = 'insert';
            tagged = diffTagText(og, amend);

            expect(amendmentType(tagged)).toBe(expected);
        });

        test('strike ', () => {
            let amend = '<p>The good dog.</p>'
            let expected = 'strike';
            tagged = diffTagText(og, amend);

            expect(amendmentType(tagged)).toBe(expected);
        });

        test('strikeinsert ', () => {
            let amend = '<p>The dog smells terrible.</p>'
            let expected = 'strikeinsert';
            tagged = diffTagText(og, amend);

            expect(amendmentType(tagged)).toBe(expected);
        });
    });


    describe('getChangedText', () => {

        describe('insert', () => {
        test('returns expected', () => {
            let tagged = '<p>The good dog smells<ins class="diffins">&nbsp;nice</ins>.</p>';
            let r = getChangedText(tagged, 'insert');

            expect(r).toBe('dog');
        });
        });
    });

});
