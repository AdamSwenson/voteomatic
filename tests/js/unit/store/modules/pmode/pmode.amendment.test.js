let _ = require('lodash');
const sinon = require('sinon');
import pa from "../../../../../../resources/js/store/modules/pmode/pmode.amendments"

describe('pmode.amendments actions', () => {

    describe('utilities', () => {

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

                expect(pa.diffTagText(og, amend)).toBe(expected);
            });
            test('strike ', () => {
                let amend = '<p>The good dog.</p>'
                let expected = '<p>The good dog<del class="diffdel">&nbsp;smells</del>.</p>';

                expect(pa.diffTagText(og, amend)).toBe(expected);
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
                tagged = pa.diffTagText(og, amend);

                expect(pa.amendmentType(tagged)).toBe(expected);
            });

            test('strike ', () => {
                let amend = '<p>The good dog.</p>'
                let expected = 'strike';
                tagged = pa.diffTagText(og, amend);

                expect(pa.amendmentType(tagged)).toBe(expected);
            });

            test('strikeinsert ', () => {
                let amend = '<p>The dog smells terrible.</p>'
                let expected = 'strikeinsert';
                tagged = pa.diffTagText(og, amend);

                expect(pa.amendmentType(tagged)).toBe(expected);
            });
        });


        describe('getChangedText', () => {

            describe('insert', () => {
                test('returns expected', () => {
                    let tagged = '<p>The good dog smells<ins class="diffins">&nbsp;nice</ins>.</p>';
                    let r = pa.getChangedText(tagged, 'insert');

                    expect(r).toBe('dog');
                });
            });
        });


        describe('replaceTags', () => {

            test('insert ', () => {
                let txt = '<p>The good dog smells<ins class="diffins">&nbsp;nice</ins>.</p>';
                let expected = '<p>The good dog smells<span class="rezzieAmendment amendmentInsert amendment88" data="88">&nbsp;nice</span>.</p>';
                expect(pa.replaceTags(txt, 88)).toBe(expected);
            });

            test('strike ', () => {
                let txt = '<p>The good dog <del class="diffdel">&nbsp;smells</del>.</p>'
                let expected = '<p>The good dog <span class="rezzieAmendment amendmentStrike amendment88" data="88">&nbsp;smells</span>.</p>';
                expect(pa.replaceTags(txt, 88)).toBe(expected);
            });


            test('mixed ', () => {
                let txt = '<p>The good<ins class="diffins">&nbsp;and handsome</ins>dog <del class="diffdel">&nbsp;smells</del>.</p>'
                let expected = '<p>The good<span class="rezzieAmendment amendmentInsert amendment88" data="88">&nbsp;and handsome</span>dog <span class="rezzieAmendment amendmentStrike amendment88" data="88">&nbsp;smells</span>.</p>';
                expect(pa.replaceTags(txt, 88)).toBe(expected);
            });
        });

    });

    describe('processText', () => {
        test('unchanged', () => {
           let txt = '<p>The smelly black dog was too stinky for the quick brown fox</p>';
           expect(pa.processText(txt, txt, 44));
        });

        test('insert ', () => {
            let og = '<p>The good dog smells.</p>';
            let am = '<p>The good dog smells nice.</p>';
            let expected = '<p>The good dog smells<span class="rezzieAmendment amendmentInsert amendment88" data="88">&nbsp;nice</span>.</p>';
            expect(pa.processText(og, am, 88)).toBe(expected);
        });

        test('strike ', () => {
            let og = '<p>The good dog smells.</p>';
            let am = '<p>The good dog.</p>'
            let expected = '<p>The good dog<span class="rezzieAmendment amendmentStrike amendment88" data="88">&nbsp;smells</span>.</p>';
            expect(pa.processText(og, am, 88)).toBe(expected);
        });


        test('mixed ', () => {
            let og = '<p>The good dog smells.</p>';
            let am = '<p>The good and handsome dog.</p>'
            let expected = '<p>The good <span class="rezzieAmendment amendmentInsert amendment88" data="88">and handsome </span>dog<span class="rezzieAmendment amendmentStrike amendment88" data="88">&nbsp;smells</span>.</p>';
            expect(pa.processText(og, am, 88)).toBe(expected);
        });

    });

    describe('tagTemplates', () => {
        describe('insertTagTemplate', () => {

            test('returns expected', () => {
                let expected = '<span class="rezzieAmendment amendmentInsert amendment345" data="345">';
                expect(pa.insertTagTemplate(345)).toBe(expected);
            });
        });

        describe('strikeTagTemplate', () => {

            test('returns expected', () => {
                let expected = '<span class="rezzieAmendment amendmentStrike amendment345" data="345">';
                expect(pa.strikeTagTemplate(345)).toBe(expected);
            });
        });
    });




    describe('actions', () => {
    });

});
