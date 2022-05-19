import MotionObjectFactory from "../../../models/MotionObjectFactory";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import Payload from "../../../models/Payload";
import * as routes from "../../../routes";

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


const actions = {
    /*
    *    doThing({dispatch, commit, getters}, thingParam) {
    *        return new Promise(((resolve, reject) => {
    *        }));
    *    },
    */

    handleForcePageReload({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            window.console.log('Caught forcePageReload ');
            // window.location.reload();
        }));
    },


    orderPageReload({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            let meeting = getters.getActiveMeeting;
            let url = routes.events.forceReload(meeting.id);

            return Vue.axios.post(url).then(() => {
                return resolve();
            });
        }));
    }
    //
    // /**
    //  * When the client is notified by the server that voting on the currently active
    //  * motion has been ended, this removes the option to try to vote and
    //  * initiates the loading of results.
    //  *
    //  *  Required Pusher payload: Full motion (stored as ended; optionally
    //  */
    // handlePublicPModeMotionClosedMessage({dispatch, commit, getters}, pusherEvent) {
    //     return new Promise(((resolve, reject) => {
    //         let ended = MotionObjectFactory.make(pusherEvent.ended);
    //
    //         // let ended = new Motion(pusherEvent.ended);
    //         // let superseding = new Motion(pusherEvent.superseding);
    //         // let original = new Motion(pusherEvent.original);
    //
    //         /* Set the current motion as closed and update isVotingAllowed */
    //         dispatch('markMotionComplete', ended).then(() => {
    //
    //             /* Navigate to results card. It will load results on mount  */
    //             // dispatch('forceNavigationToResults');
    //
    //             /* Quietly create a revised  motion if the motion which passed was an amendment */
    //             //This checks whether the motion was an amendment and if it was successful
    //             dispatch('handlePotentialAmendmentAfterVotingClosed', pusherEvent);
    //
    //             //We don't need to wait for it to finish.
    //             resolve();
    //
    //         });
    //
    //     }));
    // },
    //
    // /**
    //  * When a new motion has been created and seconded,
    //  * this sets the motion as the current motion and navigates to the
    //  * voting tab
    //  *
    //  *  Required Pusher payload: Full motion
    //  * */
    // handlePublicPModeMotionSecondedMessage({dispatch, commit, getters}, pusherEvent) {
    //     return new Promise(((resolve, reject) => {
    //         dispatch('resetMotionPendingSecond');
    //         let motion = MotionObjectFactory.make(pusherEvent.motion);
    //
    //         // let motion = new Motion(pusherEvent.motion);
    //         commit('addMotionToStore', motion);
    //         //Make it the current motion and attach relevant listeners
    //         return dispatch('setMotion', motion)
    //             .then(() => {
    //                 // dispatch('forceNavigationToHome');
    //                 return resolve(motion);
    //             });
    //     }));
    // },
    //
    // /**
    //  * Handles setting a motion as current and navigation tasks
    //  * when the chair has marked the motion as current
    //  *
    //  * Required Pusher payload: Full motion
    //  *
    //  * @param dispatch
    //  * @param commit
    //  * @param getters
    //  * @param pusherEvent
    //  * @returns {Promise<unknown>}
    //  */
    // handlePublicPModeNewCurrentMotionSetMessage({dispatch, commit, getters}, pusherEvent) {
    //     return new Promise(((resolve, reject) => {
    //         // dispatch('resetMotionPendingSecond');
    //         let motion = MotionObjectFactory.make(pusherEvent.motion);
    //
    //         // let motion = new Motion(pusherEvent.motion);
    //         commit('addMotionToStore', motion);
    //         //Make it the current motion and attach relevant listeners
    //         return dispatch('setMotion', motion)
    //             .then(() => {
    //                 // dispatch('forceNavigationToHome');
    //                 return resolve(motion);
    //             });
    //     }));
    // },
    //
    // /**
    //  * This will be run on everything when the motion closes. Thus this checks for:
    //  * - whether it was an amendment
    //  * - whether it passed
    //  *
    //  * If it was a successful amendment, this quietly creates a new motion with the
    //  * updated text
    //  *
    //  * We do not set the new motion as active. That is the job of other actions.
    //  *
    //  * This can be passed either a json (from the pusher event / response.data) or
    //  * an array of motion objects with the same keys
    //  *
    //  * @param dispatch
    //  * @param commit
    //  * @param getters
    //  */
    // handlePublicPModePotentialAmendmentAfterVotingClosed({dispatch, commit, getters}, {ended, superseding, original = null}) {
    //     return new Promise(((resolve, reject) => {
    //         //The server will return a new motion under the key superseding
    //         //if the motion was an amendment and was successful.
    //         //It returns false otherwise.
    //         if (!isReadyToRock(superseding) || superseding === false) {
    //             return resolve();
    //         }
    //
    //         //Since superseding was not false, we know that the motion which
    //         //ended was an amendment and that it was successful
    //         //So we make a new motion out of the response and add it to the store
    //         //(we don't send it to the server, since we're handling the server's response)
    //         // superseding = new Motion(superseding);
    //         superseding = MotionObjectFactory.make(superseding);
    //
    //         commit('addMotionToStore', superseding);
    //
    //         //We now need to swap the superseding motion in for the original
    //         original = getters.getMotionById(ended.applies_to);
    //
    //         //Prevent the original from being voted upon
    //         dispatch('markMotionComplete', original);
    //
    //         //Set the fact that it was superseded so that the display
    //         //can prevent it from being selected.
    //         let pl = Payload.factory({
    //             object: original,
    //             updateProp: 'superseded_by',
    //             updateVal: superseding.id
    //         });
    //         commit('setMotionProp', pl);
    //
    //         resolve();
    //     }));
    // },
    //
    //
    // /**
    //  * When the client is notified by the server that voting on a motion is now open
    //  * this handles everything.
    //  *
    //  * Required Pusher payload: Full motion
    //  *
    //  * @param dispatch
    //  * @param commit
    //  * @param getters
    //  */
    // handlePublicPModeVotingOnMotionOpenedMessage({dispatch, commit, getters}, pusherEvent) {
    //     return new Promise(((resolve, reject) => {
    //         let motion = MotionObjectFactory.make(pusherEvent.motion);
    //         // let motion = new Motion(pusherEvent.motion);
    //         //commit('addMotionToStore', motion);
    //         return dispatch('markMotionVotingOpen', motion)
    //             .then(() => {
    //                 //If it somehow wasn't the current motion
    //                 //make it the current motion and attach relevant listeners
    //                 dispatch('setMotion', motion);
    //                 dispatch('forceNavigationToVote');
    //                 return resolve(motion);
    //             });
    //     }));
    //
    // },

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
const getters = {};

export default {
    actions,
    getters,
    mutations,
    state,
}
