import HtmlDiff from 'htmldiff-js';
import Payload from "../../../models/Payload";
import * as routes from "../../../routes";
import Message from "../../../models/Message";
import BallotObjectFactory from "../../../models/BallotObjectFactory";
import Resolution from "../../../models/Resolution";
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
const strikeTag = '<del class="diffdel">';
const insertRegex = new RegExp(insertTag);
const strikeRegex = new RegExp(strikeTag);
const insertContentRegex = new RegExp('(?<=' + insertTag + ')(.*?)(?=</ins>)');
const strikeContentRegex = new RegExp('(?<=' + strikeTag + ')(.*?)(?=</del>)');

//Replacement tags
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
    let type = amendmentType(diffTaggedText);
    let r;

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

    let a = diffTaggedText.replace(insertRegex, insertTagTemplate(amendmentId));
    a = a.replace(new RegExp('</ins>'), '</span>');
    a = a.replace(strikeRegex, strikeTagTemplate(amendmentId));
    return a.replace(new RegExp('</del>'), '</span>');
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
        return new Promise(((resolve, reject) => {
            let parent = getters.getMotionById(amendment.applies_to);
            let taggedHtml = processText(parent.content, amendment.content, amendment.id);
            return resolve(taggedHtml);

        }));
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
                    // £window.console.log('prediff', rezAmend);
                    dispatch('diffTagResolutionAmendment', rezAmend).then((taggedHtml) => {
                        //We haven't saved this to store, so it is ok
                        //to update the content
                        rezAmend.content = taggedHtml;
                        // window.console.log('postdiff', rezAmend);

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

    },


    diffAmendments({dispatch, commit, getters}, originalMain) {
        return new Promise(((resolve, reject) => {
            let taggedText = originalMain.content;

            let amendments = getters.getAmendments(originalMain);

            //Compare to amendment text
            _.forEach(amendments, (amendment) => {
                let parent = getters.getMotionById(amendment.applies_to);
                let resultant = getters.getMotionById(parent.superseded_by);

                //Get tagged text
                let diff = diffTagText(originalMain.content, amendment.content);

                //Get the changed portion from the diff
                //dev Currently only works for one change
                let changed = getChangedText(diff);

                //Find the index where the tagged stuff starts
            });


        }));
    }
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

        // let secondaryAmends = getters.getMotions.filter(function (i) {
        //     if (i.applies_to === motion.id) {
        //         return i;
        //     }


    }
};

export default {
    actions,
    getters,
    mutations,
    state,
    //dev exporting these to aid in testing
    amendmentType,
    diffTagText,
    getChangedText,
    insertTagTemplate,
    strikeTagTemplate,
    replaceTags,
    processText
}
