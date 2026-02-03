import MotionObjectFactory from "../../../models/MotionObjectFactory";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import Motion from "../../../models/Motion";
import Payload from "../../../models/Payload";

const actions = {
    /*
    *    doThing({dispatch, commit, getters}, thingParam) {
    *        return new Promise(((resolve, reject) => {
    *        }));
    *    },
    */

    /**
     * When the client is notified by the server that voting on the current
     * motion has been aborted, this does the notification and relevant state
     * updates.
     * @param dispatch
     * @param state
     * @param getters
     * @param pusherEvent
     */
    handleMotionAbortedMessage({dispatch, state, getters}, pusherEvent) {
        window.console.log('motions.actions.events', 'handleMotionAbortedMessage', 24, pusherEvent);
        dispatch('handleForcePageReload');
    },

    /**
     * When the client is notified by the server that voting on the currently active
     * motion has been ended, this removes the option to try to vote and
     * initiates the loading of results.
     *
     *  Required Pusher payload: Full motion (stored as ended; optionally
     */
    handleMotionClosedMessage({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            // let ended = MotionObjectFactory.make(pusherEvent.ended);

            //Added in VOT-176
            pusherEvent.motion = pusherEvent.ended; //Action will expect the motion to be on the motion property
            return dispatch('getMotionFromEvent', pusherEvent).then((ended) => {

                // let ended = new Motion(pusherEvent.ended);
                // let superseding = new Motion(pusherEvent.superseding);
                // let original = new Motion(pusherEvent.original);

                //Added in VOT-176
                //If it somehow wasn't the current motion
                //make it the current motion and attach relevant listeners
                return dispatch('setMotion', ended).then(() => {

                    /* Set the current motion as closed and update isVotingAllowed */
                    dispatch('markMotionComplete', ended).then(() => {

                        /* Navigate to results card. It will load results on mount  */
                        dispatch('forceNavigationToResults');

                        /* Quietly create a revised  motion if the motion which passed was an amendment */
                        //This checks whether the motion was an amendment and if it was successful
                        dispatch('handlePotentialAmendmentAfterVotingClosed', pusherEvent);

                        //We don't need to wait for it to finish.
                        resolve();

                    });
                });
            });

        }));
    },

    /**
     * When a new motion has been created and seconded,
     * this sets the motion as the current motion and navigates to the
     * voting tab
     *
     *  Required Pusher payload: Full motion
     * */
    handleMotionSecondedMessage({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            dispatch('resetMotionPendingSecond');

            //Added VOT-176
            return dispatch('getMotionFromEvent', pusherEvent).then((motion) => {
                // let motion = MotionObjectFactory.make(pusherEvent.motion);

                // let motion = new Motion(pusherEvent.motion);
                commit('addMotionToStore', motion);
                //Make it the current motion and attach relevant listeners
                return dispatch('setMotion', motion)
                    .then(() => {
                        dispatch('forceNavigationToHome');
                        return resolve(motion);
                    });
            });
        }));
    },

    /**
     * Handles setting a motion as current and navigation tasks
     * when the chair has marked the motion as current
     *
     * Required Pusher payload: Full motion
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param pusherEvent
     * @returns {Promise<unknown>}
     */
    handleNewCurrentMotionSetMessage({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            // dispatch('resetMotionPendingSecond');

            //Added VOT-176
            return dispatch('getMotionFromEvent', pusherEvent).then((motion) => {
                // let motion = MotionObjectFactory.make(pusherEvent.motion);

                // let motion = new Motion(pusherEvent.motion);
                commit('addMotionToStore', motion);
                //Make it the current motion and attach relevant listeners
                return dispatch('setMotion', motion)
                    .then(() => {
                        dispatch('forceNavigationToHome');
                        return resolve(motion);
                    });
            });
        }));
    },

    /**
     * Semi-duplicative of handleNewCurrentMotionSetMessage, except that it
     * receives just the motion id and ONLY sets an existing motion as current
     * Added VOT-308.
     *
     * Expects a pusherEvent with the property:
     *      motionId
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param pusherEvent
     * @returns {Promise<unknown>}
     */
    handleSetCurrentMotionRequest({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            let motionId = pusherEvent.motionId;
            let motion = getters.getMotionById(motionId);
            window.console.log('motions.actions.events', 'handleSetCurrentMotionRequest', 149, motionId, motion);

            //this is ugly and needs to be rethought. In VOT-308 we are sometimes
            //getting reload requests when the motion doesn't already exist for an amendment
            //followed by a call to handleSetCurrentMotionRequest.
            //Doing this the dumb way for now and just checking if the motion exists, if not
            //calling reload (which should work because can use motionId) and then handling setting
            if (!isReadyToRock(motion)) {
                return dispatch('reloadMotion', motionId).then(() => {
                    //Make it the current motion and attach relevant listeners
                    return dispatch('setMotion', motion)
                        .then(() => {
                            dispatch('forceNavigationToHome');
                            return resolve(motion);
                        });
                });
            } else {
                //Make it the current motion and attach relevant listeners
                return dispatch('setMotion', motion)
                    .then(() => {
                        dispatch('forceNavigationToHome');
                        return resolve(motion);
                    });
            }


        }));
    },

    /**
     * This will be run on everything when the motion closes. Thus this checks for:
     * - whether it was an amendment
     * - whether it passed
     *
     * If it was a successful amendment, this quietly creates a new motion with the
     * updated text
     *
     * We do not set the new motion as active. That is the job of other actions.
     *
     * This can be passed either a json (from the pusher event / response.data) or
     * an array of motion objects with the same keys
     *
     * @param dispatch
     * @param commit
     * @param getters
     */
    handlePotentialAmendmentAfterVotingClosed({dispatch, commit, getters}, {ended, superseding, original = null}) {
        return new Promise(((resolve, reject) => {
            //The server will return a new motion under the key superseding
            //if the motion was an amendment and was successful.
            //It returns false otherwise.
            if (!isReadyToRock(superseding) || superseding === false) {
                return resolve();
            }

            //Since superseding was not false, we know that the motion which
            //ended was an amendment and that it was successful
            //So we make a new motion out of the response and add it to the store
            //(we don't send it to the server, since we're handling the server's response)
            // superseding = new Motion(superseding);
            superseding = MotionObjectFactory.make(superseding);

            commit('addMotionToStore', superseding);

            //We now need to swap the superseding motion in for the original
            original = getters.getMotionById(ended.applies_to);

            //Prevent the original from being voted upon
            dispatch('markMotionComplete', original);

            //Set the fact that it was superseded so that the display
            //can prevent it from being selected.
            let pl = Payload.factory({
                object: original,
                updateProp: 'superseded_by',
                updateVal: superseding.id
            });
            commit('setMotionProp', pl);

            resolve();
        }));
    },

    /**
     * Not a getter. Checks to see if there already exists a motion
     * corresponding to the one in the event. If so, it returns it.
     *
     * If it is given a field motionId, will load the existing object for that id (Added VOT-308)
     *
     * If not, it creates a new object from the event (but does not add it to store)
     *
     * Added in VOT-176 to deal with regular users being able to change the active motion locally
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param pusherEvent
     * @returns {Promise<unknown>}
     */
    getMotionFromEvent({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            //Determine whether we have received the full motion
            //or just the id
            if (_.has(pusherEvent, 'motionId')) {
                let motion = getters.getMotionById(pusherEvent.motionId);
                return resolve(motion);
            }

            //Get the existing object if possible so that we won't have
            //duplicates which different things could mutate.
            let motion = getters.getMotionById(pusherEvent.motion.id);
            if (isReadyToRock(motion)) return resolve(motion);

            return resolve(MotionObjectFactory.make(pusherEvent.motion));
        }));
    },

    /**
     * If server tells the client (via pusher) to reload a particular motion,
     * this handles it. Used when the payload is too big for pusher.
     * @param dispatch
     * @param commit
     * @param getters
     * @param pusherEvent
     * @returns {Promise<unknown>}
     */
    handleReloadMotionRequest({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            dispatch('getMotionFromEvent', pusherEvent).then((motion) => {
                dispatch('reloadMotionRequest', motion);
                resolve();
            });
        }));
        // dispatch('handleForcePageReload', pusherEvent);
    },

    /**
     * When the client is notified by the server that voting on a motion is now open
     * this handles everything.
     *
     * Required Pusher payload: Full motion
     *
     * @param dispatch
     * @param commit
     * @param getters
     */
    handleVotingOnMotionOpenedMessage({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {

            //Added VOT-176
            return dispatch('getMotionFromEvent', pusherEvent).then((motion) => {

                //If it somehow wasn't the current motion
                //make it the current motion and attach relevant listeners
                return dispatch('setMotion', motion).then(() => {

                    return dispatch('markMotionVotingOpen', motion).then(() => {

                        //If it somehow wasn't the current motion
                        //make it the current motion and attach relevant listeners
                        // dispatch('setMotion', motion);
                        dispatch('forceNavigationToVote');
                        return resolve(motion);
                    });
                });
            });
        }));

    },


};


export {actions as default}
