import Motion from "../../models/Motion";
import Message from "../../models/Message";
import * as routes from "../../routes";
import Payload from "../../models/Payload";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import Office from "../../models/Office";
import BallotObjectFactory from "../../models/BallotObjectFactory";
import {idify} from "../../utilities/object.utilities";

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

    createMotionFromTemplate({dispatch, commit, getters}, template) {
        return new Promise(((resolve, reject) => {
            let meeting = getters.getActiveMeeting;

            //send to server
            let url = routes.motions.resource();
            let d = template;
            d['meetingId'] = meeting.id;
            // let p = {'meetingId': meetingId};
            // window.console.log('sending', d);

            return Vue.axios.post(url, d)
                .then((response) => {
                    let d = response.data;

                    let statusMessage = Message.makeFromTemplate('pendingApproval');
                    // window.console.log(statusMessage);
                    dispatch('showMessage', statusMessage);
                    // commit('addToMessageQueue', statusMessage);

                    resolve();
                    //let them know the chair will need to approve
                    // alert('d');
                });
        }));
    },        //

    /**
     * Create a new motion on the server and set
     * it as the current motion.
     *
     * This is used by the regular member. It does not set the current motion
     * since a member making a motion will need it to be approved and seconded.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    createMotion({dispatch, commit, getters}, meetingId) {
        let me = this;
        return new Promise(((resolve, reject) => {
            // window.console.log('creating');
            // let statusMessage = Message.makeFromTemplate('pendingApproval');
            // window.console.log(statusMessage);
            // commit('addToMessageQueue', statusMessage);

            //send to server
            let url = routes.motions.resource();
            let p = {'meetingId': meetingId};
            // window.console.log('sending', p);
            return Vue.axios.post(url, p)
                .then((response) => {
                    //Set a message for the user telling them what's going to happen
                    let statusMessage = Message.makeFromTemplate('pendingApproval');
                    //set it on a timer
                    dispatch('showMessage', statusMessage);

                    resolve();

                    resolve();
                    // let d = response.data;
                    //
                    // let motion = new Motion(d);
                    // // let motion = new Motion(d.id, d.name, d.date);
                    // commit('addMotionToStore', motion);
                    //
                    // let pl = {meetingId: meetingId, motionId: motion.id};
                    //
                    // return dispatch('setCurrentMotion', pl)
                    //     .then(() => {
                    //         return resolve(motion);
                    //     });
                    //
                    // // commit('setMotion', motion);

                });
        }));

    },

    /**
     * Uses the current state of the draft motion to
     * create a new motion on the server
     *
     * The server will handle setting it as current, if the user is the chair or
     * soliciting the chair's approval if they are a regular member.
     *
     * NB, nothing needs to be passed in since everything relevant is stored in
     * state
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    createMotionFromDraft({dispatch, commit, getters}) {
        let meeting = getters.getActiveMeeting;
        let motion = getters.getDraftMotion;
        let me = this;
        return new Promise(((resolve, reject) => {

            //send to server
            let url = routes.motions.resource();
            // let p = {'meetingId': meetingId};
            motion['meetingId'] = meeting.id;
            // window.console.log('sending', p);
            return Vue.axios.post(url, motion)
                .then((response) => {
                    //Set a message for the user telling them what's going to happen
                    let statusMessage = Message.makeFromTemplate('pendingApproval');
                    //set it on a timer
                    dispatch('showMessage', statusMessage);

                    resolve();
                    // let d = response.data;
                    //
                    // let motion = new Motion(d);
                    // // let motion = new Motion(d.id, d.name, d.date);
                    // commit('addMotionToStore', motion);
                    //
                    // let pl = {meetingId: meetingId, motionId: motion.id};
                    //
                    // return dispatch('setCurrentMotion', pl)
                    //     .then(() => {
                    //         return resolve(motion);
                    //     });
                    //
                    // // commit('setMotion', motion);

                });
        }));

    },


    /**
     * Create a new motion on the server and set
     * it as the current motion
     *
     * dev This probably won't end up being used. Keeping the set current motion logic for now
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    createMotionByChair({dispatch, commit, getters}, meetingId) {
        let me = this;
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource();
            let p = {'meetingId': meetingId};
            // window.console.log('sending', p);
            return Vue.axios.post(url, p)
                .then((response) => {
                    let d = response.data;

                    let motion = new Motion(d);
                    // let motion = new Motion(d.id, d.name, d.date);
                    commit('addMotionToStore', motion);

                    let pl = {meetingId: meetingId, motionId: motion.id};

                    return dispatch('setCurrentMotion', pl)
                        .then(() => {
                            return resolve(motion);
                        });

                    // commit('setMotion', motion);

                });
        }));

    },


    createSubsidiaryMotion({dispatch, commit, getters}, payload) {
        let me = this;

        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource();
            // window.console.log('sending', p);
            return Vue.axios.post(url, payload)
                .then((response) => {
                    //Set a message for the user telling them what's going to happen
                    let statusMessage = Message.makeFromTemplate('pendingApproval');
                    //set it on a timer
                    dispatch('showMessage', statusMessage);

                    resolve();
                    // let d = response.data;
                    //
                    // let motion = new Motion(d);
                    // // let motion = new Motion(d.id, d.name, d.date);
                    // commit('addMotionToStore', motion);
                    //
                    // let pl = {meetingId: meetingId, motionId: motion.id};
                    //
                    // return dispatch('setCurrentMotion', pl)
                    //     .then(() => {
                    //         return resolve(motion);
                    //     });
                    //
                    // // commit('setMotion', motion);

                });
        }));

        // //send to server
        //     let url = routes.motions.resource();
        //     window.console.log('sending', payload);
        //     return Vue.axios.post(url, payload)
        //         .then((response) => {
        //             let d = response.data;
        //
        //             let motion = new Motion(d);
        //             // let motion = new Motion(d.id, d.name, d.date);
        //             commit('addMotionToStore', motion);
        //
        //             let pl = {meetingId: payload.meetingId, motionId: motion.id};
        //
        //             return dispatch('setCurrentMotion', pl)
        //                 .then(() => {
        //                     return resolve(motion);
        //                 });
        //
        //             // commit('setMotion', motion);
        //
        //         });
        // }));

    },


    deleteMotion({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource(motion.id);
            return Vue.axios.delete(url)
                .then((response) => {
                    let d = response.data;

                    //remove it from the list of motions
                    commit('deleteMotion', motion);

                    //check whether it is the currently set motion
                    let activeMotino = getters.getActiveMotion;
                    if (activeMotino.id === motion.id) {
                        //we need to remove it and set another in its place
                        let newActive = getters.getStoredMeetings[0];
                        dispatch('setMotion', newActive);

                    }
                    return resolve()
                });
        }));

    },


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

                    //This will be the updated motion we just sent to the server
                    let endedMotion = response.data.ended;
                    //If the motion was an amendment, the server will
                    //also return a new version of the motion which was amended.
                    //Otherwise, this will just be false
                    let superseding = response.data.superseding;

                    dispatch('setMotion', motion);

                    let pl = Payload.factory({
                        object: motion,
                        updateProp: 'isComplete',
                        updateVal: endedMotion.is_complete
                    });

                    //we leave it as the currently set motion so that
                    //the results tab will provide results for the
                    //immediate past motion.
                    //Instead, we just update the completed property on the
                    //motion
                    commit('setMotionProp', pl);


                    //Handle swapping in the new motion if there was an amendment.
                    //todo This will be fixed in VOT-72
                    if (superseding) {
                        let original = getters.getMotionById(superseding.superseded_by);
                        //remove that from the store (but don't delete from server!)
                        commit('deleteMotion', original);
                        //make a new motion and add it to the store (but not to the server)
                        let motion = new Motion(d);
                        commit('addMotionToStore', motion);

                    }

                    resolve()
                });
        }));

    },

    /** When a new motion has been created and seconded,
     * this sets the motion as the current motion and navigates to the
     * voting tab
     * */
    handleMotionSecondedMessage({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            dispatch('resetMotionPendingSecond');
            let motion = new Motion(pusherEvent.motion);
            commit('addMotionToStore', motion);
            //Make it the current motion and attach relevant listeners
            return dispatch('setMotion', motion)
                .then(() => {
                    dispatch('forceNavigationToHome');
                    return resolve(motion);
                });
        }));
    },

    handleNewCurrentMotionSetMessage({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            // dispatch('resetMotionPendingSecond');
            let motion = new Motion(pusherEvent.motion);
            commit('addMotionToStore', motion);
            //Make it the current motion and attach relevant listeners
            return dispatch('setMotion', motion)
                .then(() => {
                    dispatch('forceNavigationToHome');
                    return resolve(motion);
                });
        }));
    },

    /**
     * When the client is notified by the server that voting on the currently active
     * motion has been ended, this removes the option to try to vote and
     * initiates the loading of results.
     *
     */
    handleVotingEndedOnCurrentMotion({dispatch, commit, getters}, endedMotion, supersedingMotion = false) {
        //This will be the updated motion we just sent to the server

        //If the motion was an amendment, the server will
        //also return a new version of the motion which was amended.
        //Otherwise, this will just be false
        return new Promise(((resolve, reject) => {
            /* ----------------- Set the current motion as closed ------------ */
            dispatch('markMotionComplete', endedMotion).then(() => {

                /* ----------------- Load results and navigate to results card ------------ */
                // commit('setResultsNavTrigger', true);
                dispatch('forceNavigationToResults');

                /* ----------------- Quietly create the revised main motion  ------------ */
                //todo Check if successful and if amendment
//todo This will be fixed in VOT-72
                dispatch('createNewMotionAfterSuccessfulAmendment', endedMotion)
                // //Handle swapping in the new motion if there was an amendment.
                // if (supersedingMotion) {
                //     let original = getters.getMotionById(supersedingMotion.superseded_by);
                //     //remove that from the store (but don't delete from server!)
                //     commit('deleteMotion', original);
                //     //make a new motion and add it to the store (but not to the server)
                //     let motion = new Motion(d);
                //     commit('addMotionToStore', motion);
                // }

                resolve()
            });

        }));
    },

    /**
     * When the client is notified by the server that voting on a motion is now open
     * this handles everything.
     * @param dispatch
     * @param commit
     * @param getters
     */
    handleVotingOnMotionOpenedMessage({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            let motion = new Motion(pusherEvent.motion);
            //commit('addMotionToStore', motion);
            return dispatch('markMotionVotingOpen', motion)
                .then(() => {
                    //If it somehow wasn't the current motion
                    //make it the current motion and attach relevant listeners
                    dispatch('setMotion', motion);
                    dispatch('forceNavigationToVote');
                    return resolve(motion);
                });
        }));

    },

    /**
     * Create a draft motion on the client. This is what the user
     * edits before they click 'make motion'. After that, editing would
     * be done on the main motion.
     *
     * This may help with the problem of the user getting pulled away while
     * working on their motion (VOT-75)
     *
     * @param dispatch
     * @param commit
     * @param getters
     */
    initializeDraftMainMotion({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            let motion = new Motion({
                type: 'main',
                requires: 0.5,
                debatable: true
            });
            commit('setDraftMotion', motion);
            resolve();
        }));

    },


    /**
     * If an amendment passes, we need to quietly create a new main motion with the
     * updated text.
     *
     * We do not set the new motion as active. That is the job of other actions.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param amendmentMotion
     * @param supersedingMotion
     */
    createNewMotionAfterSuccessfulAmendment({dispatch, commit, getters}, amendmentMotion, supersedingMotion = false) {
        //todo This will be fixed in VOT-72

        //Handle swapping in the new motion if there was an amendment.
        if (supersedingMotion) {
            let original = getters.getMotionById(supersedingMotion.superseded_by);
            //remove that from the store (but don't delete from server!)
            commit('deleteMotion', original);
            //make a new motion and add it to the store (but not to the server)
            let motion = new Motion(d);
            commit('addMotionToStore', motion);
        }

    },

    /**
     * Gets the motion from the server
     *
     * //dev Does this actually work? Shouldn't it add to store too?
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @returns {Promise<unknown>}
     */
    loadMotion({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource(motion.id);
            return Vue.axios.get(url)
                .then((response) => {
                    let d = response.data;
                    let motion = new Motion(d);
                    // let motion = new Motion(d.id, d.name, d.date);
                    dispatch('setMotion', motion);
                    resolve()
                });
        }));
    },

    /**
     * Populates the store of ids which represent
     * motions the user has already cast a ballot on.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param meetingId
     * @returns {Promise<unknown>}
     */
    loadMotionsUserHasVotedUpon({dispatch, commit, getters}, meetingId) {
        window.console.log("Loading voted upon motions");
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.castVotes.getVotedMotions(meetingId);
            return Vue.axios.get(url).then((response) => {
                _.forEach(response.data, (d) => {

                    // todo or should we be storing ids? need to decide how best to do comparisons

                    // todo should we clear the store first? Can the list contain motions with duplicate ids?
                    // todo
                    // let motion = new Motion(d);
                    commit('addVotedUponMotion', d.id);
                });
                return resolve();
            });
        }));
    },

    loadMotionsForMeeting({dispatch, commit, getters}, meeting) {
        //we need this to determine whether election or regular
        // let meeting = getters.getMeetingById(meetingId);

        window.console.log('Loading motions for meeting ', meeting);
        return new Promise(((resolve, reject) => {

            //send to server
            let url = routes.motions.getAllMotionsForMeeting(idify(meeting));
            return Vue.axios.get(url)
                .then((response) => {
                    //Need to do this so that we don't have to
                    //check motions for their meetings every time
                    //we access the stack.
                    commit('clearMotions');

                    _.forEach(response.data, (d) => {
                        let m = BallotObjectFactory.make(d, meeting);
                        // let m = null;
                        // if(isReadyToRock(meeting.is_election) && meeting.is_election){
                        //      m= new Office(d);
                        // }else{
                        //     m = new Motion(d);
                        // }

                        // let motion = new Motion(d.id, d.name, d.date);
                        commit('addMotionToStore', m);
                        if (d.is_current) {
                            dispatch('setMotion', m)
                        }
                    });

                    resolve()

                });
        }));
    },

    loadMotionTypesAndTemplates({dispatch, commit, getters}) {
        window.console.log("Loading motion types and templates");
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.templates();
            return Vue.axios.get(url)
                .then((response) => {
                    commit('setMotionTemplates', response.data);

                    let url2 = routes.motions.types();
                    return Vue.axios.get(url2)
                        .then((response) => {
                            //todo Figure out easiest way to use loaded types. If not easy, then ignore. The point is to make it easier to keep definitions on client and server in sync.

                            // Motion.prototype.amendmentNames = response.data.amendment;
                            // Motion.prototype.proceduralMotionNames = response.data.amendment;
                            return resolve();
                        });
                });
        }));
    },

    /**
     * Sets the is_complete property on the provided motion
     * to true and performs any other actions which are needed when
     * we learn that voting has ended on a currently active motion
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


    // markMotionInOrder({dispatch, commit, getters}, motion) {
    //     return new Promise(((resolve, reject) => {
    //         let url = routes.motions.inOrder(motion.id);
    //         return Vue.axios.post(url)
    //             .then((response) => {
    //                 resolve();
    //             });
    //     }));
    // },
    //
    //
    // markMotionOutOfOrder({dispatch, commit, getters}, motion) {
    //     return new Promise(((resolve, reject) => {
    //         let url = routes.motions.outOfOrder(motion.id);
    //         return Vue.axios.post(url)
    //             .then((response) => {
    //                 resolve();
    //             });
    //     }));
    // },
    //
    //
    // /**
    //  * Removes a motion seeking a second and resets to null
    //  * @param dispatch
    //  * @param commit
    //  * @param getters
    //  * @returns {Promise<unknown>}
    //  */
    // resetMotionPendingSecond({dispatch, commit, getters}) {
    //     return new Promise(((resolve, reject) => {
    //         commit('setMotionPendingSecond', null);
    //         resolve();
    //     }));
    // },
    //
    // /**
    //  * Tells server that motion has been seconded
    //  * @param dispatch
    //  * @param commit
    //  * @param getters
    //  * @param meetingId
    //  * @param motionId
    //  * @returns {Promise<unknown>}
    //  */
    // secondMotion({dispatch, commit, getters}, motion) {
    //     return new Promise(((resolve, reject) => {
    //         //send to server
    //         let url = routes.motions.secondMotion(motion.id);
    //         return Vue.axios.post(url)
    //             .then((response) => {
    //                 // //this assumes the motion being seconded is the current motion.
    //                 // //that should be normally the case except for high
    //                 // //precedence motions which can be made while something
    //                 // //else is waiting for a second. Those will be very
    //                 // //rare cases.
    //                 // let pl = Payload(
    //                 //     {
    //                 //         updateProp: 'seconded',
    //                 //         updateVal: response.data.seconded
    //                 //     });
    //                 // commit('setMotionProp', pl);
    //
    //                 return resolve();
    //
    //             });
    //     }));
    // },


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

                });
        }));
    },

    /**
     * Wraps the commit which sets a particular motion as the
     * one being voted on so that other listeners can be attached.
     *
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
                    dispatch('handleVotingEndedOnCurrentMotion', motion);
                })
            // .listen("NewMotionCreated", (e) => {
            //
            //     window.console.log('Received broadcast event motions', e);
            //     dispatch('handleNewMotionCreated', motion);
            // })
            //     .listen("MotionSeekingSecond", (e) => {
            //         window.console.log('Received broadcast event motions', e);
            //         dispatch('handleMotionSeekingSecond', motion);
            //     })
            //     .listen("MotionSeconded", (e) => {
            //         window.console.log('Received broadcast event motions', e);
            //         //Switches to the motion which has now been approved and seconded
            //         dispatch('handleMotionSeconded', motion);
            //     });
            //
            // if(getters.getIsAdmin){
            //     let chairChannel = `chair.${motion.id}`;
            //     Echo.private(chairChannel)
            //         .listen('MotionNeedingApproval', (e) => {
            //             window.console.log('Received chair broadcast', e);
            //
            //             dispatch('handleNewMotionCreated', motion);
            //
            //         });
            //
            // }


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
                });
        }));
    },

    //
    // setMotionPendingSecond({dispatch, commit, getters}, motion) {
    //     return new Promise(((resolve, reject) => {
    //         commit('setMotionPendingSecond', motion);
    //         resolve();
    //     }));
    // },

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
            //todo consider whether worth rolling back
            commit('setMotionProp', payload)

            let motion = getters.getActiveMotion;
            window.console.log('updateMotion', payload, motion);

            //send to server
            let url = routes.motions.resource(motion.id);
            return Vue.axios.post(url, {data: motion, _method: 'put'})
                .then((response) => {
                    let d = response.data;
                    resolve()
                });
        }));
    },


    /**
     * Updates properties of the draft motion the user
     * is working on. Does not tell the server anything.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param payload
     * @returns {Promise<unknown>}
     */
    updateDraftMotion({dispatch, commit, getters}, payload) {
        return new Promise(((resolve, reject) => {
            //make local change only
            commit('setDraftMotionProp', payload)
            resolve();
        }));
    }
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
