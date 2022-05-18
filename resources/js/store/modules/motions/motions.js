import Motion from "../../../models/Motion";
import Message from "../../../models/Message";
import * as routes from "../../../routes";
import Payload from "../../../models/Payload";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import Office from "../../../models/Office";
import BallotObjectFactory from "../../../models/BallotObjectFactory";
import {idify} from "../../../utilities/object.utilities";
import Resolution from "../../../models/Resolution";
import MotionObjectFactory from "../../../models/MotionObjectFactory";

import Create from './motions.actions.crud';
import Events from './motions.actions.events';
import Loaders from './motions.actions.loaders';

/**
 * Created by adam on 2020-07-30.
 */


/**
 * Returns the object stored in the array which
 * has the provided id
 *
 * @param storageArray
 * @param id
 * @returns {*}
 */
function getById(storageArray, id) {
    // return function ( state, id ) {
    let r = storageArray.filter(function (i) {
        if (i.id === id) {
            return i;
        }
    });
    return r[0];
}

const state = {

    /**
     * The id of the motion being voted on,
     * currently edited, or whose
     * results are being reported
     */
    currentMotion: null,

    /**
     * Store of loaded motions
     */
    motions: [],

    draftMotion: null,


    /**
     * Motions from this meeting which the user has already
     * cast a vote on and should be locked out of voting again
     */
    motionIdsUserHasVotedUpon: [],

    standardMotionDefinitions: [],
};

const mutations = {
    addMotionToStore: (state, motionObject) => {
        // window.console.log(motionObject);
        //todo double check that there is no reason to have duplicates or raise an error
        let mi = -1;
        _.forEach(state.motions, function (m) {
            if (m.id === motionObject.id) {
                mi = 1;
            }
        });

        if (mi === -1) {
            state.motions.push(motionObject);
        }


        // state.motions.push(motionObject);
        // Vue.set(state, 'motion', payload);

    },

    /**
     * Empties the list of motions. Used when changing
     * meetings / elections
     * @param state
     */
    clearMotions: (state) => {
        state.motions = [];
    },

    clearDraftMotion: (state) => {
        state.draftMotion = null;
    },


    deleteMotion: (state, motionObject) => {
        // let idx = state.motions.indexOf(motionObject);
        // state.motions.pop(idx);
        _.remove(state.motions, function (motion) {
            return motion.id === motionObject.id;
        });

    },

    setDraftMotion: (state, motionObject) => {
        state.draftMotion = motionObject;
    },

    setDraftMotionProp: (state, {updateProp, updateVal}) => {
        Vue.set(state.draftMotion, updateProp, updateVal);
    },


    /**
     * Sets the provided motion object as
     * the currently active motion
     *
     * This should not be called directly by anything except the setMotion action
     *
     * @param state
     * @param motionObject
     */
    setMotion: (state, motionObject) => {
        Vue.set(state, 'currentMotion', motionObject.id);
    },

    setMotionTemplates: (state, templates) => {
        Vue.set(state, 'standardMotionDefinitions', templates);
    },

    /**
     * Pushes a motion id into the list of motion is
     * which the user has already voted on. This is
     * used for client side restrictions on display.
     *
     * If the motion object is needed, it should be loaded
     * separately via the id.
     *
     * @param state
     * @param motionId
     */
    addVotedUponMotion: (state, motionId) => {
        //todo double check that there is no reason to have duplicates or raise an error
        if (!state.motionIdsUserHasVotedUpon.includes(motionId)) {
            state.motionIdsUserHasVotedUpon.push(motionId);
        }
    },

    /**
     * Updates a property on the motion object
     * @param state
     * @param prop
     * @param val
     */
    setMotionProp: (state, {updateProp, updateVal}) => {
        // window.console.log(updateProp, updateVal);
        let currentMotion = getById(state.motions, state.currentMotion);

        Vue.set(currentMotion, updateProp, updateVal);

        // Vue.set(state.currentMotion, updateProp, updateVal);
    },


};

const actions = {
    ...Create,
    ...Events,
    ...Loaders,



    /**
     * Used by the chair to close the vote and prevent further casting of
     * ballots
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @returns {Promise<unknown>}
     */
    endVotingOnMotion({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.endVoting(motion.id);
            return Vue.axios.post(url)
                .then((response) => {
                    //The server will return a response containing motions with the
                    //keys:
                    //      ended
                    //      superseding
                    //However, we're just going to wait for the pusher notification
                    //and handle all the updating from there.
                    //Though going to pass this back for specialty purposes like the chair's mark passed button in pmode
                    resolve(response);

                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));

    },



    /**
     * Sets the is_complete property on the provided motion
     * to true. Also sets is_voting_allowed to false.
     *
     * This does not change the motion's status as the currently active motion.
     * That, inter alia, the results tab to show results for the motion.
     * It is up to other actions to change the now completed motion's status and
     * set another as active.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param endedMotion
     * @param motion
     * @returns {Promise<unknown>}
     */
    markMotionComplete({dispatch, commit, getters}, endedMotion) {
        return new Promise(((resolve, reject) => {
            let pl = Payload.factory({
                object: endedMotion,
                updateProp: 'isComplete',
                updateVal: true
            });

            commit('setMotionProp', pl);

            //This will keep the voting page from appearing
            //to allow someone who hasn't voted the opportunity to vote
            //(they will be blocked by the server)
            let pl2 = Payload.factory({
                object: endedMotion,
                updateProp: 'isVotingAllowed',
                updateVal: false
            });
            commit('setMotionProp', pl2);

            resolve();
        }));
    },

    /**
     * Sets the is_voting_allowed property on the provided motion
     * @param dispatch
     * @param commit
     * @param getters
     * @param endedMotion
     * @returns {Promise<unknown>}
     */
    markMotionVotingOpen({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            let pl = Payload.factory({
                object: motion,
                updateProp: 'isVotingAllowed',
                updateVal: true
            });

            commit('setMotionProp', pl);

            resolve();
        }));
    },


    /**
     * Sets the motion as the current one on the server
     * and updates the local store
     * @param dispatch
     * @param commit
     * @param getters
     * @param meetingId
     * @param motionId
     * @returns {Promise<unknown>}
     */
    setCurrentMotion({dispatch, commit, getters}, {meetingId, motionId}) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.setCurrentMotion(meetingId, motionId);
            return Vue.axios.post(url)
                .then((response) => {
                    let motion = getters.getMotionById(motionId);

                    //Commit is wrapped in another action to set the websocket handler
                    dispatch('setMotion', motion).then(() => {
                        return resolve()
                    });

                })
                .catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));
    },

    /**
     * Wraps the commit which sets a particular motion as the
     * one being voted on so that other listeners can be attached.
     *
     * ALMOST EVERYTHING WHICH AFFECTS WHICH MOTION IS CURRENT SHOULD DISPATCH THIS ACTION
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param payload
     */
    setMotion({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            //first reset the nav triggers since we may not
            //want selecting the motion to automatically force everyone somewhere
            commit('resetNavTriggers');
            //reset any votes cast count
            dispatch('resetCounts');
            //Actually set as current
            commit('setMotion', motion)
            window.console.log('currentMotion set', motion);
            let channel = `motions.${motion.id}`;
            Echo.private(channel)
                .listen("MotionClosed", (e) => {
                    window.console.log('Received broadcast event motions', e);
                    // if(getters.isInPublicPmode === true){
                    //     dispatch('handlePublicPModeMotionClosedMessage')
                    // }else{
                        dispatch('handleMotionClosedMessage', e);
                    // }

                })

            window.console.log('Websocket listener set for current motion on channel ', channel);
            return resolve();
        }));
    },


    /**
     * Used by the chair to open voting for all users on the
     * provided motion
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     */
    startVotingOnMotion({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.openVoting(motion.id);
            return Vue.axios.post(url, motion)
                .then((response) => {
                    let d = response.data;
                    resolve();
                    //we don't do anything here since the push message will trigger
                    //everything
                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));
    },

    /**
     * Sends new field entries to server and
     * adds them to the currently active motion
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @returns {Promise<unknown>}
     */
    updateMotion({dispatch, commit, getters}, payload) {
        return new Promise(((resolve, reject) => {
            //make local change first
            //todo consider whether worth rolling back on failure
            commit('setMotionProp', payload)

            let motion = getters.getActiveMotion;
            window.console.log('updateMotion', payload, motion);

            //send to server
            let url = routes.motions.resource(motion.id);
            return Vue.axios.post(url, {data: motion, _method: 'put'})
                .then((response) => {
                    let d = response.data;
                    resolve()
                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));
    },



};


const getters = {

    /**
     * Returns the currently set motion object.
     * This will be the motion that is currently being voted on,
     * edited, or whose results are being displayed.
     * @param state
     * @returns {null|{set: module.exports.computed.motion.set, get: (function(): module.exports.computed.motion.$store.getters.getMotion)}|{set: function(*=): void, get: function(): *}|(function(): *)|(function(): Motion)|Motion}
     */
    getActiveMotion: (state) => {
        return getById(state.motions, state.currentMotion)
        // return state.currentMotion;
    },

    getDraftMotion: (state) => {
        return state.draftMotion;
    },

    getMotionById: (state) => (id) => {
        // return function ( state, id ) {
        // window.console.log(id, state, id);
        let r = state.motions.filter(function (i) {
            if (i.id === id) {
                return i;
            }
        });
        return r[0];
    },
    // }( state, id )

    getMotionByIndex: (state) => (index) => {
        return state.motions[index];
    },


    /**
     * Returns all motion objects which have
     * been loaded from the server. May include motions
     * the user has voted upon and un-voted motions.
     * @param state
     * @returns {[]|(function(): ([]|__webpack_exports__.default.computed.$store.getters.getStoredMotions))|{resource: (function(): string), getAllMotionsForMeeting: (function(*): string)}|{resource: (function(*=): string), getAllMotionsForMeeting: (function(*): string)}|{mutations: {addMotionToStore: function(*, *=): void, setMotion: function(*=, *=): void, addVotedUponMotion: function(*, *=): void}, state: {motion: null, motions: [], motionIdsUserHasVotedUpon: []}, getters: {getMotionsUserVotedUpon: function(*): *, getMotionIdsUserVotedUpon: function(*): [], getMotion: function(*): (null|{set: module.exports.computed.motion.set, get: (function(): module.exports.computed.motion.$store.getters.getMotion)}|{set: (function(*=): void), get: (function(): *)}|(function(): *)|(function(): Motion)|Motion), getStoredMotions: function(*): *}, actions: {createMotion({dispatch: *, commit?: *, getters: *}): Promise<unknown>, loadMotionsForMeeting({dispatch: *, commit?: *, getters: *}, *=): Promise<unknown>, loadMotionsUserHasVotedUpon({dispatch: *, commit?: *, getters: *}, *=): Promise<unknown>, loadMotion({dispatch: *, commit?: *, getters: *}, *): Promise<unknown>, updateMotion({dispatch: *, commit: *, getters: *}, *=): Promise<unknown>}}|(function(): ([]|default.computed.$store.getters.getStoredMotions))}
     */
    getStoredMotions: (state) => {
        return state.motions;
    },

    getMotions: (state) => {
        return state.motions;
    },

    // getMotionPendingSecond: (state) => {
    //     return state.motionPendingSecond;
    // },

    getMotionsUserVotedUpon: (state) => {
        let out = [];
        _.forEach(state.motionIdsUserHasVotedUpon, (motionId) => {
            let r = state.motions.filter(function (i) {
                if (i.id === motionId) {
                    return i;
                }
            });
            out.push(r[0]);
        })
        return out;
        //
        // let r = state.motions.filter(function (i) {
        //     if (i.id === id) {
        //         return i;
        //     }
        // });
        // return r[0];
        // return state.motionIdsUserHasVotedUpon;
    },

    getMotionIdsUserVotedUpon: (state) => {
        let out = [];
        _.forEach(state.motionIdsUserHasVotedUpon, (mid) => {
            out.push(mid);
        })
        return out;
    },

    /**
     * Whether the user has voted on the motion which is
     * currently active
     * @param state
     */
    hasVotedOnCurrentMotion: (state) => {
        return state.motionIdsUserHasVotedUpon.indexOf(state.currentMotion) > -1

        // return state.motionIdsUserHasVotedUpon.indexOf(state.currentMotion.id) > -1
    },


    hasVotedOnMotion: (state) => (motion) => {
        return state.motionIdsUserHasVotedUpon.indexOf(motion.id) > -1

        // return state.motionIdsUserHasVotedUpon.indexOf(state.currentMotion.id) > -1
    },

    getResolutions: (state) => {
        return state.motions.filter((m) => {
            return m.is_resolution === true;
        });
    },

    /**
     * Not actually a getter from state; doing this way to keep
     * the definitions centrally. Returns the
     * dict with all the properties of the standard motion
     * templates.
     * @param state
     */
    getStandardMotionDefinitions: (state) => {
        return state.standardMotionDefinitions;

        // return [
        //     {
        //         name: 'Adjourn',
        //         content: "That the meeting be adjourned.",
        //         description: "Meeting comes to an end. This is amendable with respect " +
        //             "to when the next meeting will be, if specified",
        //         requires: 0.5,
        //         type: 'procedural-main',
        //         amendable: true
        //     },
        //
        //
        //     {
        //         name: 'Committee of the Whole',
        //         content: "That the body convene as a committee of the whole with this body's Chair as its Chair ",
        //         description: "The formal deliberative process is suspended. The body" +
        //             " may work informally on an issue. No votes taken while in the committee of the whole " +
        //             "are binding on the main body but they may be used to advise the main body on what to do. " +
        //             "To communicate from the committee of the whole, the committee " +
        //             "of the whole should vote to Rise and Report",
        //         requires: 0.5,
        //         type: 'procedural-main',
        //         amendable: true
        //
        //     },
        //
        //     {
        //         name: 'Previous Question (Call the Question)',
        //         content: "That the pending question is called",
        //         description: "If approved, all debate ends on the pending motion and " +
        //             "the body moves immediately to a vote on the pending motion. If fails," +
        //             "debate continues on the pending motion",
        //         requires: 0.66,
        //         type: 'procedural-subsidiary',
        //         amendable: false
        //     },
        //     {
        //         name: 'Place on the Table',
        //         content: "The pending motion is placed on the table",
        //         description: "All action on the motion is paused so the body can attend to " +
        //             "other business. There is no scheduled time to resume action. Action " +
        //             "will resume upon a majority vote to Take from the Table. That motion may" +
        //             "be made whenever no main motion is pending",
        //         requires: 0.5,
        //         type: 'procedural-subsidiary',
        //         amendable: false
        //     },
        //
        //
        //     {
        //         name: 'Recess',
        //         content: "That the body recess.",
        //         description: "We take a break. This can be qualified to " +
        //             "say how long. The how long part is amendable.",
        //         requires: 0.5,
        //         type: 'procedural-main',
        //         amendable: true
        //     },
        //
        //     {
        //         name: 'Reconsider (with notice)',
        //         content: "That the body reconsider the motion that ",
        //         description: "",
        //         requires: 0.5,
        //         type: 'procedural-main',
        //         amendable: false
        //
        //     },
        //
        //     {
        //         name: 'Reconsider (without notice)',
        //         content: "That the body reconsider the motion that ",
        //         description: "",
        //         requires: 0.66,
        //         type: 'procedural-main',
        //         amendable: false
        //
        //     },
        //
        //
        // ]


    }
};


export default {
    actions,
    getters,
    mutations,
    state,
}
