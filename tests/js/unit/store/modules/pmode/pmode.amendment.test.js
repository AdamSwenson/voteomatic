let _ = require('lodash');
const sinon = require('sinon');
import pa from "../../../../../../resources/js/store/modules/pmode/pmode.amendments"
import Resolution from "../../../../../../resources/js/models/Resolution";


const textStylerFactoryFactory = (amendmentId, type, text) => {
    return `<text-styler-factory type=\'${type}\' v-bind:amendment-id=\'${amendmentId}\' text=\'${text}\'></text-styler-factory>`;
};
const tss = textStylerFactoryFactory;
const resolutionFactory = (id, content, superseded_by = null) => {

    return new Resolution({
        is_resolution: true,
        type: 'resolution',
        id: id,
        applies_to: null,
        superseded_by: superseded_by,
        content: content,
        info: {
            formattedContent: content
        }
    });
};

const resolutionAmendmentFactory = (id, applies_to, superseded_by, content, formattedContent) => {

    return new Resolution({
        is_resolution: true,
        type: 'amendment',
        id: id,
        applies_to: applies_to,
        superseded_by: superseded_by,
        content: content,
        info: {
            formattedContent: formattedContent
        }
    });
};


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


            describe('second order amendments', () => {
                let og;
                let amendment;
                let expected;
                let ogAmendmentId;
                let newAmendmentId;
                describe('primary amendment: Insert', () => {
                    beforeEach(() => {
                        ogAmendmentId = 88;
                        newAmendmentId = 44;
                        og = "<p>The good dog smells<text-styler-factory type='insert' v-bind:amendment-id='88' text=' nice'></text-styler-factory>.</p>"
                    });

                    test('secondary amendment: Insert', () => {
                        amendment = "<p>The good dog smells<text-styler-factory type='insert' v-bind:amendment-id='88' text=' very nice'></text-styler-factory>.</p>";
                        expected = `<p>The good dog smells<text-styler-factory type='insert' v-bind:amendment-id='88' text='<ins class="diffins">&nbsp;very</ins> nice'></text-styler-factory>.</p>`;

                        expect(pa.diffTagText(og, amendment)).toBe(expected);

                    });
                });
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
                // let expected = '<p>The good dog smells<span class="rezzieAmendment amendmentInsert amendment88" data="88">&nbsp;nice</span>.</p>';
                let expected = "<p>The good dog smells<text-styler-factory type='insert' v-bind:amendment-id='88' text='&nbsp;nice'></text-styler-factory>.</p>"

                expect(pa.replaceTags(txt, 88)).toBe(expected);
            });

            test('strike ', () => {
                let txt = '<p>The good dog <del class="diffdel">&nbsp;smells</del>.</p>'
                let expected = "<p>The good dog <text-styler-factory type='strike' v-bind:amendment-id='88' text='&nbsp;smells'></text-styler-factory>.</p>"

                // let expected = '<p>The good dog <span class="rezzieAmendment amendmentStrike amendment88" data="88">&nbsp;smells</span>.</p>';
                expect(pa.replaceTags(txt, 88)).toBe(expected);
            });


            test('mixed ', () => {
                let txt = '<p>The good<ins class="diffins">&nbsp;and handsome</ins>dog <del class="diffdel">&nbsp;smells</del>.</p>'
                // let expected = '<p>The good<span class="rezzieAmendment amendmentInsert amendment88" data="88">&nbsp;and handsome</span>dog <span class="rezzieAmendment amendmentStrike amendment88" data="88">&nbsp;smells</span>.</p>';
                let expected = "<p>The good<text-styler-factory type='insert' v-bind:amendment-id='88' text='&nbsp;and handsome'></text-styler-factory>dog <text-styler-factory type='strike' v-bind:amendment-id='88' text='&nbsp;smells'></text-styler-factory>.</p>"

                expect(pa.replaceTags(txt, 88)).toBe(expected);
            });
        });

    });

    describe('processText', () => {
        describe('first order amendments', () => {

            test('unchanged', () => {
                let txt = '<p>The smelly black dog was too stinky for the quick brown fox</p>';
                expect(pa.processText(txt, txt, 44));
            });

            test('insert ', () => {
                let og = '<p>The good dog smells.</p>';
                let am = '<p>The good dog smells nice.</p>';
                let expected = "<p>The good dog smells<text-styler-factory type='insert' v-bind:amendment-id='88' text='&nbsp;nice'></text-styler-factory>.</p>"

                // let expected = '<p>The good dog smells<span class="rezzieAmendment amendmentInsert amendment88" data="88">&nbsp;nice</span>.</p>';
                expect(pa.processText(og, am, 88)).toBe(expected);
            });

            test('strike ', () => {
                let og = '<p>The good dog smells.</p>';
                let am = '<p>The good dog.</p>'
                let expected = "<p>The good dog<text-styler-factory type='strike' v-bind:amendment-id='88' text='&nbsp;smells'></text-styler-factory>.</p>"
                // let expected = '<p>The good dog<span class="rezzieAmendment amendmentStrike amendment88" data="88">&nbsp;smells</span>.</p>';
                expect(pa.processText(og, am, 88)).toBe(expected);
            });


            test('mixed ', () => {
                let og = '<p>The good dog smells.</p>';
                let am = '<p>The good and handsome dog.</p>'
                let expected = "<p>The good <text-styler-factory type='insert' v-bind:amendment-id='88' text='and handsome '></text-styler-factory>dog<text-styler-factory type='strike' v-bind:amendment-id='88' text='&nbsp;smells'></text-styler-factory>.</p>"

                // let expected = '<p>The good <span class="rezzieAmendment amendmentInsert amendment88" data="88">and handsome </span>dog<span class="rezzieAmendment amendmentStrike amendment88" data="88">&nbsp;smells</span>.</p>';
                expect(pa.processText(og, am, 88)).toBe(expected);
            });
        });

        describe('second order amendments', () => {
            let og;
            let amendment;
            let expected;
            let ogAmendmentId;
            let newAmendmentId;
            describe('primary amendment: Insert', () => {
                beforeEach(() => {
                    ogAmendmentId = 88;
                    newAmendmentId = 44;
                    og = "<p>The good dog smells<text-styler-factory type='insert' v-bind:amendment-id='88' text=' nice'></text-styler-factory>.</p>"
                });

                test('secondary amendment: Insert', () => {
                    amendment = "<p>The good dog smells<text-styler-factory type='insert' v-bind:amendment-id='88' text=' very nice'></text-styler-factory>.</p>";
                    expected = `<p>The good dog smells
<text-styler-factory type='insert' v-bind:amendment-id='88' text=' <text-styler-factory type=\'insert\' v-bind:amendment-id=\'44\' text=\' very\'></text-styler-factory>.</p>`;

                    expect(pa.processText(og, amendment, newAmendmentId)).toBe(expected);

                });

                test('secondary amendment: Strike', () => {
                });

                test('secondary amendment: Strike insert', () => {
                });


                test('strike insert into insertion', () => {
                });

            });

            describe('primary amendment: Strike', () => {

                test('secondary amendment: Insert (increase scope - contiguous)', () => {
                });

                test('secondary amendment: Strike (reduce scope - contiguous)', () => {
                });


                test('secondary amendment: Strike (reduce scope - noncontiguous)', () => {
                });


            });

            describe('primary amendment: Strike insert', () => {

                test('secondary amendment: Insert', () => {
                });

                test('secondary amendment: Strike', () => {
                });

                test('secondary amendment: Strike insert', () => {
                });


                test('strike insert into insertion', () => {
                });

            });

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


    describe('getters', () => {


    });


    describe('dev', () => {
        let a1011 = "<h4 class=\"rezzieTitle\">Est sit magnam recusandae eos.</h4><div><text-styler-factory type='insert' v-bind:amendment-id='1011' text='Dog says wag&nbsp;'></text-styler-factory></div><div><br></div>";
        let a1012 = "<h4 class=\"rezzieTitle\">Est sit magnam recusandae eos.</h4><div>Dog says<text-styler-factory type='insert' v-bind:amendment-id='1012' text='&nbsp;bark and'></text-styler-factory> wag&nbsp;</div><div><br></div>";
        let a1013 = "<h4 class=\"rezzieTitle\">Est sit magnam recusandae eos.</h4><div>Dog says<text-styler-factory type='insert' v-bind:amendment-id='1012' text='&nbsp;bark and'></text-styler-factory> wag&nbsp;</div><div><br></div>";


        test('how tags factory', () => {
            let r = pa.diffTagText(a1011, a1013);
            expect(r).toBe('adlfaf');

            let received = "<h4 class=\"rezzieTitle\">Est sit magnam recusandae eos.</h4><div><ins class=\"diffins\">Dog says</ins><text-styler-factory type='insert' v-bind:amendment-id='1012' text='&nbsp;bark and'></text-styler-factory><ins class=\"diffins\">&nbsp;wag&nbsp;</ins></div><div><br></div>"

        });

        test('how replaces tags around  factory', () => {

            let received = "<h4 class=\"rezzieTitle\">Est sit magnam recusandae eos.</h4><div><ins class=\"diffins\">Dog says</ins><text-styler-factory type='insert' v-bind:amendment-id='1012' text='&nbsp;bark and'></text-styler-factory><ins class=\"diffins\">&nbsp;wag&nbsp;</ins></div><div><br></div>"
            let r = pa.replaceTags(received, 1011);
            //aha. Not replacing every instance.
            // r = pa.replaceTags(r, 1011);
            expect(r).toBe('adlfaf');

        });

        test('truncate', () => {

            let insertLeadingRegex = new RegExp(/.+?(?=<ins)/, 'g');
            let t = "<p>Words and things which are good <ins class='things'>stuff inserted</ins> more things";

            let expected = "<p>Words and things which are good ";
            let result = t.match(insertLeadingRegex);
            expect(result[0]).toBe(expected);

            const getLeadingWords = (text, regex, numWords = 3) => {
                let words = _.words(result[0], /[^, ]+/g);
                let keep = _.slice(words, -numWords);
                return _.join(keep, ' ');
            };

            let w = _.words(result[0], /[^, ]+/g);
            let ws = _.slice(w, -3);
            expect(ws).toStrictEqual(['which', 'are', 'good'])

            expect(getLeadingWords(result[0], insertLeadingRegex)).toBe('which are good');
        });

        test('trailing', () => {
// \?(.*)$

            let insertTrailingRegex = new RegExp('(?<=ins>).*$', 'g');

            let t = '<p>Words and things which are good <ins class="diffins">stuff inserted</ins> more things that are things';

            let expected = " more things that are things";
            let result = t.match(insertTrailingRegex);
            expect(result[0]).toBe(expected);

            const getTrailingWords = (text, regex, numWords = 3) => {
                let words = _.words(result[0], /[^, ]+/g);
                let keep = _.slice(words, 0, numWords);
                return _.join(keep, ' ');
            };

            expect(getTrailingWords(result[0], insertTrailingRegex)).toBe('more things that');


        });

        describe('truncateTextAroundChanges ', () => {

            test('single change', () => {
                let singleInsert = '<p>Words and things which are good <ins class="diffins">stuff inserted</ins> more things that are things.';
                let result = pa.truncateTextAroundChanges(singleInsert);
                let exp3 = '...which are good <ins class="diffins">stuff inserted</ins> more things that';
                expect(result).toBe(exp3);
            });

            test('multiple changes', () => {
                let multiInsert = '<p>Words and things which are good <ins class="diffins">stuff inserted</ins> more things that are things. Followed ' +
                    'by another <ins class="diffins">bunch of inserted stuff</ins> in this location';
                let result = pa.truncateTextAroundChanges(multiInsert);
                let exp3 = '...which are good <ins class="diffins">stuff inserted</ins> more things that';
                let exp4 = '...Followed by another <ins class="diffins">bunch of inserted stuff</ins> in this location';
                expect(result).toBe(exp3 + exp4);
            });
        });


        //
        // let result1 =
        //     let
        // result2 = truncateTextAroundChanges(multiInsert);
        // let exp3 = '...which are good <ins class="diffins">stuff inserted</ins> more things that';
        // let exp4 = '...Followed by another <ins class="diffins">bunch of inserted stuff</ins> in this location';
        //

        // const icr = new RegExp("<ins(.*?)</ins>", 'g');
        //
        // let insertContent = multiInsert.match(icr);
        // expect(insertContent.length).toBe(2);
        //
        //
        // let out = '';
        // _.forEach(insertContent, (ic) => {
        //     let leadingRx = new RegExp('.+?(?=' + ic + ')', 'g');
        //     let insertTrailingRegex = new RegExp('(?<=' + ic + ').*$', 'g');
        //     let l = multiInsert.match(leadingRx);
        //     let t = multiInsert.match(insertTrailingRegex);
        //     // let trailingRx = = new RegExp('.+?(?=' + ic + ')', 'g');
        //     // let insertLeadingRegex = new RegExp(//, 'g');
        //     let leading = pa.getLeadingWords(l[0]);
        //     let trailing = pa.getTrailingWords(t[0])
        //     out += `...${leading} ${ic} ${trailing}`;
        // });
        //
        // let exp3 = '...which are good <ins class="diffins">stuff inserted</ins> more things that';
        // let exp4 = '...Followed by another <ins class="diffins">bunch of inserted stuff</ins> in this location';
        //
        // expect(out).toBe(exp3 + exp4);

        // const insertTag = '<ins class="diffins">';
        // const insertTag2 = '<ins class="diffmod">';
        //
        //    const insertContentRegex = new RegExp('(?<=' + insertTag + ')(.*?)(?=</ins>)|' + '(?<=' + insertTag2 + ')(.*?)(?=</ins>)', 'g');
        //    const icr = new RegExp("<ins(.*?)</ins>", 'g');
        //
        //
        //    let insertContent = t.match(icr);
        //
        //    expect(insertContent.length).toBe(1);
        //    expect(insertContent[0]).toBe('<ins class="diffins">stuff inserted</ins>');
        //
        //    if(insertContent.length > 0) {
        //        _.forEach(insertContent, (ic) => {
        //            let r = new RegExp('.+?(?=' + insertTag + ic + '<\\ins>)', 'g');
        // let a = t.match(r);
        // expect(a[0]).toBe('<p>Words and things which are good <ins class="diffins">stuff inserted</ins>');
        //        });
        //    }
        // let leading = getLeadingWords(text)

    });

    describe('VOT-207', () => {

        test('a', () => {
            let a = 'a b c e f';
            let b = 'a b c d e f';
            let expected = 'a b c d e f';
            a = _.words(a, /[^, ]+/g);
            b = _.words(b, /[^, ]+/g);

            let orderBySize = (first, second) => {
                if (first.length > second.length) return [first, second];
                return [second, first];
            };

            let c = orderBySize(a, b);
            let bigger = c[0];
            let smaller = c[1];
            let out = [];
            let k = 0;
            while (bigger.length > 0) {
                let w = bigger.pop();
                out.push(w);
                if (w === _.last(smaller)) {
                    smaller.pop();
                }
            }
            if (smaller.length > 0) {
                while (smaller.length > 0) {
                    let v = smaller.pop();
                    out.push(v);
                }

                // for (let i = 0; i <= bigger.length; i++) {
                // if(bigger[i] === smaller[k]){
                //     out += bigger[i];
                //     k += 1;
                // }
            }
            out = _.reverse(out);
            out = _.join(out, ' ');

            expect(out).toBe(expected);

        });



        test('c', () => {
            const insertTag = '<ins class="diffins">';
            const insertTag2 = '<ins class="diffmod">';
            const strikeTag = '<del class="diffdel">';
            const strikeTag2 = '<del class="diffmod">';
            const insertRegex = new RegExp(insertTag + '|' + insertTag2, 'g');
            const strikeRegex = new RegExp(strikeTag + '|' + strikeTag2, 'g');
            const insertContentRegex = new RegExp('(?<=' + insertTag + ')(.*?)(?=</ins>)|' + '(?<=' + insertTag2 + ')(.*?)(?=</ins>)', 'g');
            const strikeContentRegex = new RegExp('(?<=' + strikeTag + ')(.*?)(?=</del>)|' + '(?<=' + strikeTag2 + ')(.*?)(?=</del>)', 'g');

            //there has been an insertion in the past
            let mainId = 3;
            let prevAmendId = 2;
            let mainContent = "The small dog barks everyday";
            let mainFmt = "The small dog barks " + tss(prevAmendId, 'insert', 'everyday');

            let amendId = 4;
            //What we see in the editor window
            let amendContent = "The small stinky dog barks everyday";
            let expected = "The small" + tss(amendId, 'insert', '&nbsp;stinky') + " dog barks " +  tss(prevAmendId, 'insert', 'everyday');

            //get the difference between the contents
            let r = pa.processText(mainContent, amendContent, amendId);

            //now compare the previous formatted text to the newly tagged text
            //to get the historical stuff
            let diff = pa.diffTagText(mainFmt, r);

            //now remove the excess tags
            diff = diff.replace(insertRegex, '');
            diff = diff.replaceAll(new RegExp('</ins>', 'g'), '');

expect(diff).toBe(expected);
        });


        test('b', () => {
            //nb the changed text will always be longer because we add stuff
            //every time we remove stuff and the original stuff is part of the new thing

            let a = 'The small stinky dog barks everyday';
            let b = 'The small <removed>stinky</removed> dog barks everyday';
            // let expected = 'a b c d e f';
            let expected = b;
            a = _.reverse(_.words(a, /[^, ]+/g));
            b = _.reverse(_.words(b, /[^, ]+/g));

            let orderBySize = (first, second) => {
                if (first.length > second.length) return [first, second];
                return [second, first];
            };

            let c = orderBySize(a, b);
            let bigger = c[0];
            let smaller = c[1];
            let out = [];
            let k = 0;
            while (bigger.length > 0) {
                let w = bigger.pop();
                out.push(w);
                if (w === _.last(smaller)) {
                    smaller.pop();
                }
            }
            if (smaller.length > 0) {
                while (smaller.length > 0) {
                    let v = smaller.pop();
                    out.push(v);
                }

                // for (let i = 0; i <= bigger.length; i++) {
                // if(bigger[i] === smaller[k]){
                //     out += bigger[i];
                //     k += 1;
                // }
            }
            out = _.reverse(out);
            out = _.join(out, ' ');

            expect(out).toBe(expected);

        });


        test('j', () => {
            let a = 'a b c e f';
            let b = 'a b c d e f';
            let expected = 'a b c d e f';

            a = _.words(a, /[^, ]+/g);
            b = _.words(b, /[^, ]+/g);


            // let r = _.union(a, b);
            // r = _.join(r, ' ')
            // expect(r).toBe();

        });


        test('f', () => {

            let original = "This text has been here <text-styler-factory type='insert' v-bind:amendment-id='1011' text='Dog says wag&nbsp;'></text-styler-factory> also this text too";
            let amendId = 2;
            let changed = "This is new text has been here <text-styler-factory type='insert' v-bind:amendment-id='1011' text='Dog says wag&nbsp;'></text-styler-factory> also this text too";
            let expected = "This <text-styler-factory type='insert' v-bind:amendment-id='" + amendId + "' text='is new'></text-styler-factory> text has been here <text-styler-factory type='insert' v-bind:amendment-id='1011' text='Dog says wag&nbsp;'></text-styler-factory> also this text too";


            let result = pa.processText(original, changed, amendId);
            expect(result).toBe(expected);

        });

        describe('diffTagResolutionAmendment', () => {
            let dispatch;
            let commit;
            let getters = {};
            let amendment;
            let parent;
            let main;
            let result;
            let s = {dispatch, commit, getters};

            test('primary amendment', () => {
                main = new Resolution({
                    is_resolution: true,
                    type: 'resolution',
                    id: 1,
                    applies_to: null,
                    superseded_by: null,
                    content: "<p>This is the text that was here from the start</p>",
                    info: {
                        formattedContent: "<p>This is the text that was here from the start</p>"
                    }
                });

                amendment = new Resolution({
                    is_resolution: true,
                    type: 'amendment',
                    id: 2,
                    applies_to: 1,
                    superseded_by: null,
                    content: "<p>This is the text that was here from the start. But this is new.</p>",
                    info: {
                        formattedContent: null
                    }
                });

                let expectedAmendmentFC = "<p>This is the text that was here from the start. <text-styler-factory type='insert' v-bind:amendment-id='2' text='But this is new.'></p>";
// let gmid = new sinon.stub();

                getters = {
                    getMotionById: () => {
                    }
                }
                let gmid = new sinon.stub(getters, 'getMotionById');
                gmid.returnsThis(main);
                result = pa.actions.diffTagResolutionAmendment(s, amendment);

                expect(result).toBe(expectedAmendmentFC);

            });

        });


        describe('ddd', () => {

            test('aaaaa', () => {
                let leadingRx = new RegExp('.+?(?=' + ic + ')', 'g');

                let t = "a b c d<text-thing>dog is wag</text-thing> e f g h i j k";
                let k =  t.match(trailingRegex);

                let j = t.match(new RegExp('(?:\<text-thing).*$', 'g'));
                window.console.log(j);
                window.console.log(k);

            });

            test('a', () => {
                let trailingRegex = new RegExp('(?<=<text-thing>).*$', 'g');

                let t = "1 2 3 4 5 6 a b c d<text-thing>dog is wag</text-thing> e f g h i j k";
               let k =  t.match(trailingRegex);

                    let j = t.match(new RegExp('.+?(?:\<text-thing).*$', 'g'));

                // let j = t.match(new RegExp('(?:\<text-thing).*$', 'g'));
                window.console.log('newer', j);
                window.console.log('older', k);
expect(j).toBe(k);




            });


        })

    });
})
;
