import * as routes from "../../routes";
import Motion from "../../models/Motion";
import {getById} from "../../utilities/object.utilities";
import Message from "../../models/Message";

const state = {
    /** If a motion has been made and is awaiting a second, it is stored here*/
    motionPendingSecond: null,

    //todo This needs to be a stack so pending motions aren't lost if top one is rejected
    motionsPendingApproval: []
};

const mutations = {
    setMotionPendingSecond: (state, motion) => {
        state.motionPendingSecond = motion;
    },

    addMotionPendingApproval: (state, motion) => {
        state.motionsPendingApproval.push(motion);
    },

    removeMotionPendingApproval: (state, motion) => {
        _.remove(state.motionsPendingApproval, function (m) {
            return motion.id === m.id;
        });
    }


    /*
    *   addThing: (state, thing) => {
    *        state.things.push(thing);
    *    }
    */

};


const actions = {
    /** When a new motion has been created by a member, this asks the chair to approve
     * it as in order.
     * */
    handleMotionNeedingApprovalMessage({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            let motion = new Motion(pusherEvent.motion);
            commit('addMotionPendingApproval', motion);
            resolve();
        }));
    },

    handleMotionSeekingSecondMessage({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            let motion = new Motion(pusherEvent.motion);
            window.console.log('motion seeking second', motion);
            commit('setMotionPendingSecond', motion);
            resolve();
        }));
    },

    handleMotionMarkedOutOfOrderMessage({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            let motion = new Motion(pusherEvent.motion);
            let m = Message.makeFromTemplate('notApproved', motion);
            window.console.log('motion out of order', m);

            dispatch('showMessage', m);
            resolve();
        }));
    },

    /**
     * If no one seconds a motion, it dies
     * @param dispatch
     * @param commit
     * @param getters
     * @param pusherEvent
     * @returns {Promise<unknown>}
     */
    handleNoSecondObtainedMessage({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            //todo Will there ever be a case where need to check which motion it is?
            dispatch('resetMotionPendingSecond').then(() => {
                let motion = new Motion(pusherEvent.motion);
                let m = Message.makeFromTemplate('noSecond', motion);
                // window.console.log(m);
                dispatch('showMessage', m);
                resolve();
            });
        }));
    },


    markMotionInOrder({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            let url = routes.motions.inOrder(motion.id);
            return Vue.axios.post(url)
                .then((response) => {
                    commit('removeMotionPendingApproval', motion);
                    resolve();
                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));
    },


    markMotionOutOfOrder({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            let url = routes.motions.outOfOrder(motion.id);
            return Vue.axios.post(url)
                .then((response) => {
                    commit('removeMotionPendingApproval', motion);
                    resolve();
                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));
    },

    markNoSecondObtained({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            let url = routes.motions.secondMotion(motion.id);
            Vue.axios.delete(url).then((response) => {
                dispatch('resetMotionPendingSecond').then(() => {
                    resolve();
                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
            });

        }));
    },

    /**
     * Removes a motion seeking a second and resets to null
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    resetMotionPendingSecond({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            commit('setMotionPendingSecond', null);
            resolve();
        }));
    },

    /**
     * Tells server that motion has been seconded
     * @param dispatch
     * @param commit
     * @param getters
     * @param meetingId
     * @param motionId
     * @returns {Promise<unknown>}
     */
    secondMotion({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.secondMotion(motion.id);
            return Vue.axios.post(url)
                .then((response) => {
                    dispatch('resetMotionPendingSecond').then(() => {
                        return resolve();
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

    setMotionPendingSecond({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            commit('setMotionPendingSecond', motion);
            resolve();
        }));
    },

};


const getters = {
    getMotionPendingSecond: (state) => {
        return state.motionPendingSecond;
    },

    nextMotionNeedingApproval: (state) => {
        return state.motionsPendingApproval[0];
    }

};

export default {
    actions,
    getters,
    mutations,
    state,
}