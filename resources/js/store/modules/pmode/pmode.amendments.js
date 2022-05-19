import HtmlDiff from 'htmldiff-js';
import Payload from "../../../models/Payload";
import * as routes from "../../../routes";
import Message from "../../../models/Message";
import BallotObjectFactory from "../../../models/BallotObjectFactory";
import Resolution from "../../../models/Resolution";
import {idify} from "../../../utilities/object.utilities";
import _ from "lodash";

const state = {
    //things: []
};

const mutations = {
    /*
    *   addThing: (state, thing) => {
    *        state.things.push(thing);
    *    }
    */

};


const insertTag = '<ins class="diffins">';
const insertTag2 = '<ins class="diffmod">';
const strikeTag = '<del class="diffdel">';
const strikeTag2 = '<del class="diffmod">';
const insertRegex = new RegExp(insertTag + '|' + insertTag2, 'g');
const strikeRegex = new RegExp(strikeTag + '|' + strikeTag2, 'g');
const insertContentRegex =null; //new RegExp('(?<=' + insertTag + ')(.*?)(?=</ins>)|' + '(?<=' + insertTag2 + ')(.*?)(?=</ins>)', 'g');
const strikeContentRegex = null; //new RegExp('(?<=' + strikeTag + ')(.*?)(?=</del>)|' + '(?<=' + strikeTag2 + ')(.*?)(?=</del>)', 'g');

const textStylerCloseTag = "</text-styler-factory>"; //"\'></text-styler-factory>";
//Replacement tags
const textStylerFactoryAdder = (amendmentId, type) => {
    return `<text-styler-factory type=\'${type}\' v-bind:amendment-id=\'${amendmentId}\'>`;
    // return `<text-styler-factory type=\'${type}\' v-bind:amendment-id=\'${amendmentId}\' text=\'`;
    // return `<text-styler-factory type='${type}' text='${text}' v-bind:amendment-id='${amendmentId}'></text-styler-factory>`;
}

const insertTagTemplate = (amendmentId) => {

    return `<span class="rezzieAmendment amendmentInsert amendment${amendmentId}" data="${amendmentId}">`;
};

const strikeTagTemplate = (amendmentId) => {
    return `<span class="rezzieAmendment amendmentStrike amendment${amendmentId}" data="${amendmentId}">`;
};

/**
 * Determines the type of amendment.
 * Possible returns:
 *      strike
 *      insert
 *      strikeinsert
 * @returns {string|boolean}
 */
const amendmentType = (diffTaggedText) => {
    let out = '';
    if (strikeRegex.test(diffTaggedText)) {
        out += 'strike';
    }
    if (insertRegex.test(diffTaggedText)) {
        out += 'insert'
    }
    return out;
};

/**
 * Returns the amendment text tagged with
 * <ins class="diffins">  and
 * <del class="diffdel">
 * @returns {string|*|string}
 */
const diffTagText = (originalText, amendmentText) => {
    if (_.isUndefined(originalText) || _.isNull(originalText)) return ''
    if (_.isUndefined(amendmentText) || _.isNull(amendmentText)) return ''

    let diffHtml = HtmlDiff.execute(originalText, amendmentText);
    return diffHtml;
    // (?<=<pre>)(.*?)(?=</pre>)
};

const getChangedText = (diffTaggedText) => {
    //dev todo having trouble with using amendment type so temp made a param
    // let type = amendmentType(diffTaggedText);
    // let r;

    switch (type) {
        case 'insert' :
            return insertContentRegex.exec(diffTaggedText);
            break;
        case 'strike':
            return strikeContentRegex.exec(diffTaggedText);
            break;
        case 'strikeinsert':
            break;
    }

};


const replaceTags = (diffTaggedText, amendmentId) => {
    let a = diffTaggedText.replaceAll(insertRegex, textStylerFactoryAdder(amendmentId, 'insert'));
    // a = a.replaceAll(new RegExp('</ins>', 'g'), textStylerCloseTag);
    a = a.replaceAll(strikeRegex, textStylerFactoryAdder(amendmentId, 'strike'));
    return a.replaceAll(new RegExp('</del>|</ins>', 'g'), textStylerCloseTag);


    // let a = diffTaggedText.replace(insertRegex, insertTagTemplate(amendmentId));
    // a = a.replace(new RegExp('</ins>'), '</span>');
    // a = a.replace(strikeRegex, strikeTagTemplate(amendmentId));
    // return a.replace(new RegExp('</del>'), '</span>');
};

/**
 * Diffs the two pieces of text and returns tagged html
 * using our span classes etc.
 * This is the main function to call
 * @param originalText
 * @param amendmentText
 */
const processText = (originalText, amendmentText, amendmentId) => {
    let diffed = diffTagText(originalText, amendmentText);
    return replaceTags(diffed, amendmentId);
};

/**
 *
 * @param text
 * @param regex Regex which identifies
 * @param numWords
 * @returns {string}
 */
const getLeadingWords = (text, numWords = 3) => {
    let words = _.words(text, /[^, ]+/g);
    let keep = _.slice(words, -numWords);
    return _.join(keep, ' ');
};

const getTrailingWords = (text, numWords = 3) => {
    let words = _.words(text, /[^, ]+/g);
    let keep = _.slice(words, 0, numWords);
    return _.join(keep, ' ');
};
const truncateTextAroundChanges = (text, numWords = 3) => {
    const changedTextIncludingTagsRegex = new RegExp("<ins(.*?)</ins>|<del(.*?)</del>", 'g');

    //First we get the altered section including its tags
    let alteredContent = text.match(changedTextIncludingTagsRegex);

    let out = '';
    _.forEach(alteredContent, (ic) => {
        // good: /(?:\/)([^#]+)(?=#*)/
        // bad: /(?<=\/)([^#]+)(?=#*)/
        //https://stackoverflow.com/questions/51568821/works-in-chrome-but-breaks-in-safari-invalid-regular-expression-invalid-group

        let leadingRx = new RegExp('.+?(?=' + ic + ')', 'g');
        let trailingRegex = new RegExp('(?<=' + ic + ').*$', 'g');
        let l = text.match(leadingRx);
        let t = text.match(trailingRegex);
        // let trailingRx = = new RegExp('.+?(?=' + ic + ')', 'g');
        // let insertLeadingRegex = new RegExp(//, 'g');
        let leading = getLeadingWords(l[0], numWords);
        let trailing = getTrailingWords(t[0], numWords)
        out += `...${leading} ${ic} ${trailing}...`;
    });

    return out;
};

// const truncateAroundChanges = (text, numWords) => {
//     let insertLeadingRegex = new RegExp(/.+?(?=<ins)/, 'g');
//     let insertTrailingRegex = new RegExp('(?<=ins>).*$', 'g');
//     let strikeLeadingRegex = new RegExp(/.+?(?=<ins)/, 'g');
//     let strikeTrailingRegex = new RegExp('(?<=ins>).*$', 'g');
// let out = '';
//     let insertContent = text.match(insertContentRegex);
//     if(insertContent.length > 0){
//         _.forEach(insertContent, (ic) => {
//             let r = new RegExp('.+?(?=' + insertTag + ic + '<\\ins>)', 'g');
//
//         });
//         let leading = getLeadingWords(text)
//     }
//     }


const actions = {

    /**
     * After a resolution amendment is created, this will return
     * text updated with our expected tags.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param amendment
     * @returns {Promise<unknown>}
     */
    diffTagResolutionAmendment({dispatch, commit, getters}, amendment) {
        // return new Promise(((resolve, reject) => {

        //First we diff content against the immediate parent to get what just changed
        let parent = getters.getMotionById(amendment.applies_to);

        //dev parent's formatted content contains correct history

        let taggedHtml = processText(parent.content, amendment.content, amendment.id);
        //dev this taggedhtml is ONLY the most recent alteration

        window.console.log('+++amendment', amendment, 'parent', parent, parent.isResolutionAmendment);
        //now compare the previous formatted text to the newly tagged text
        //to get the historical stuff. NB, the trick in VOT-207 seems to be
        //to reverse and use the original as the parent so that it just adds
        //the amendment tags
        let diff = diffTagText(taggedHtml, parent.formattedContent);

        //Additional text stylers in the original will have been added
        //with insert tags
        //now remove the excess tags
        diff = diff.replaceAll(new RegExp(insertTag2 + '(.*?)</ins>', 'g'), '');
        diff = diff.replaceAll(new RegExp(strikeTag2 + '(.*?)</del>', 'g'), '');
        // diff = diff.replace(new RegExp(strikeTag + '(.*?)</del>', 'g'), '');
        // diff = diff.replace(new RegExp(insertTag + '(.*?)</ins>', 'g'), '');

        //remove the tags but not the content
        //aimed at removing superfluous additions inside text-styler-factory tags
        diff = diff.replaceAll(insertRegex, '');
        diff = diff.replaceAll(strikeRegex, '');
        diff = diff.replaceAll(new RegExp('</ins>|</del>', 'g'), '');

        taggedHtml = diff;

        //To handle VOT-197 we need to check if this is a secondary amendment
        if (parent.isResolutionAmendment) {

            diff = diff.replace(new RegExp(insertTag + '(.*?)</ins>', 'g'), '');
            diff = diff.replace(new RegExp(strikeTag + '(.*?)</del>', 'g'), '');


            // window.console.log('secondary');
            // //We have a secondary amendment, so we need to diff the tagged content
            // //against the primary amendment's parent so that it reflects the primary amendment too
            // let main = getters.getMotionById(parent.applies_to);
            //
            // //We diff and tag against the main motion but set the amendment id to the
            // //parent's id
            // taggedHtml = processText(main.formattedContent, taggedHtml, parent.id);
            // window.console.log('secondary amendment', amendment, 'primary amendment', parent, 'main ', main);
            // window.console.log('secondary tagged', taggedHtml);
        }

        return taggedHtml;

    },

    /**
     * Receives same payload as createSubsidiaryMotion
     * @param dispatch
     * @param commit
     * @param getters
     * @param payload
     * @returns {Promise<unknown>}
     */
    createResolutionAmendment({dispatch, commit, getters}, payload) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource();

            // window.console.log('sending', p);
            Vue.axios.post(url, payload)
                .then((response) => {

                    //Create a resolution object. This will normally be handled
                    //by pusher, but we need the object's id to update the
                    //tagged text
                    let rezAmend = new Resolution(response.data);

                    // window.console.log('response', response.data.info.formattedContent);
                    //dev up to this point, the formatted content correctly contains history
                    // window.console.log('rezAmend', rezAmend.info.formattedContent);

                    dispatch('diffTagResolutionAmendment', rezAmend).then((taggedHtml) => {

                        //dev here we have added the new change but lost previous changes to formattedContent

                        // window.console.log('dispatched diffTagResolutionAmendment', taggedHtml);
                        //We haven't saved this to store, so it is ok
                        //to update the content
                        //NB, split formatted content and content in VOT-190 / VOT-197

                        //dev this tagged html lacks the history
                        rezAmend.formattedContent = taggedHtml;

                        //send to server
                        let url = routes.motions.resource(rezAmend.id);
                        Vue.axios.post(url, {data: rezAmend, _method: 'put'})
                            .then((response) => {
                                // window.console.log(response);
                                return resolve()
                                // return dispatch('updateMotion', p).then(() => {
                                // return resolve();
                            });
                    });

                    //Set a message for the user telling them what's going to happen
                    let statusMessage = Message.makeFromTemplate('pendingApproval');
                    //set it on a timer
                    dispatch('showMessage', statusMessage);

                })
                .catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));

    }
    ,


    // diffAmendments({dispatch, commit, getters}, originalMain) {
    //     return new Promise(((resolve, reject) => {
    //         let taggedText = originalMain.content;
    //
    //         let amendments = getters.getAmendments(originalMain);
    //
    //         //Compare to amendment text
    //         _.forEach(amendments, (amendment) => {
    //             let parent = getters.getMotionById(amendment.applies_to);
    //             let resultant = getters.getMotionById(parent.superseded_by);
    //
    //             //Get tagged text
    //             let diff = diffTagText(originalMain.content, amendment.content);
    //
    //             //Get the changed portion from the diff
    //             //dev Currently only works for one change
    //             let changed = getChangedText(diff);
    //
    //             //Find the index where the tagged stuff starts
    //         });
    //
    //
    //     }));
    // }
    /*
    *    doThing({dispatch, commit, getters}, thingParam) {
    *        return new Promise(((resolve, reject) => {
    *        }));
    *    },
    */
};

/**
 *
 *    getThingViaId: (state) => (thingId) => {
 *        return state.things.filter(function (c) {
 *            return c.thing_id === thingId;
 *        })
 *    },
 *
 *
 *    getThing: (state, getters) => {}
 */
const getters = {

        getAmendments: (state, getters) => (motion) => {
            let primaryAmends = getters.getMotions.filter(function (i) {
                if (i.applies_to === motion.id) {
                    return i;
                }
            });
        },
        // /**
        //  * Returns any motions which apply to the provided motion
        //  * motion : Motion object or motion id
        //  */
        // getByAppliesToId: (state, getters) => (appliedToMotion) => {
        //     let motionId = idify(appliedToMotion);
        //     window.console.log('checking applies to ', motionId);
        //     return getters.getMotionById(motionId);
        //
        // }

        // let secondaryAmends = getters.getMotions.filter(function (i) {
        //     if (i.applies_to === motion.id) {
        //         return i;
        //     }


    }
;

export default {
    actions,
    getters,
    mutations,
    state,
    //dev exporting these to aid in testing
    amendmentType,
    diffTagText,
    // getChangedText,
    insertTagTemplate,
    strikeTagTemplate,
    replaceTags,
    processText,
    getTrailingWords,
    getLeadingWords,
    truncateTextAroundChanges
}
