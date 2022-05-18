import Vote from "../../../models/Vote";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MotionObjectFactory from "../../../models/MotionObjectFactory";

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

    /**
     * When in parliamentarian mode and not actually conducting votes
     * this fabricates one yes vote records it, then closes voting
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @returns {Promise<unknown>}
     */
    markMotionAsPassed({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            let vote = new Vote(
                {
                    motionId: motion.id,
                    isYay: true
                });
            dispatch('startVotingOnMotion', motion).then(() => {
                dispatch('castMotionVote', vote).then(() => {
                    dispatch('endVotingOnMotion', motion).then((response) => {
                        // if(isReadyToRock(response.data.superseding)){
                        //     let supers = MotionObjectFactory.make(response.data.superseding);
                        //     // window.console.log('next', supers);
                        //     //dev This was introduced during VOT-207. It causes an error message, I think because the new motion doesn't yet exist elsewhere on client
                        //     //dev commented out because causes to fail when program gets results for next motion
                        //     dispatch('setCurrentMotion', {meetingId: response.data.superseding.meeting_id, motionId : supers.id}).then(() => {
                        //     return resolve();
                        //     });
                        // }else{
                            return resolve();
                        // }
                    });
                });
            });

        }));
    },


    /**
     * When in parliamentarian mode and not actually conducting votes
     * this fabricates one no vote records it, then closes voting
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @returns {Promise<unknown>}
     */
    markMotionAsFailed({dispatch, commit, getters}, motion) {

        return new Promise(((resolve, reject) => {
            let vote = new Vote(
                {
                    motionId: motion.id,
                    isYay: false
                });

            dispatch('startVotingOnMotion', motion).then(() => {
                dispatch('castMotionVote', vote).then(() => {
                    dispatch('endVotingOnMotion', motion).then((response) => {
//                         if(isReadyToRock(response.data.superseding)){
//                             let supers = MotionObjectFactory.make(response.data.superseding);
//                             // window.console.log('next', supers);
//                             //dev This was introduced during VOT-207. It causes an error message, I think because the new motion doesn't yet exist elsewhere on client
// //dev commented out because causes to fail when program gets results for next motion
//                             // dispatch('setCurrentMotion', {meetingId: response.data.superseding.meeting_id, motionId : supers.id}).then(() => {
//                             //     return resolve();
//                             // });
//                         }else{
                            return resolve();
                        // }
                    });
                });
            });
        }));

    },

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
const getters = {};

export default {
    actions,
    getters,
    mutations,
    state,
}
