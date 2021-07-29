/**
 * Created by adam on 2020-07-30.
 */

import motion from "../../models/Motion";
import * as routes from "../../routes";
import {idify} from "../../utilities/object.utilities";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import MotionResult from "../../models/MotionResult";
import Payload from "../../models/Payload";

/**
 * Utility. Can't use the object.utilities.getById since
 * we need to look up by motion id
 * @param storageArray
 * @param motionId
 * @returns {*}
 */
function getById(storageArray, motionId) {
    // return function ( state, id ) {
    let r = storageArray.filter(function (i) {
        if (i.motionId === motionId) {
            return i;
        }
    });
    return r[0];
}

function createUpdatePayloadsFromResponse(resultObj, response) {
    let out = [];
    _.forEach(response.data, (v, k) => {
        if (k !== 'motionId') {
            let pl = Payload.factory({
                object: resultObj,
                updateProp: k,
                updateVal: v
            });
            out.push(pl);
        }
    });
    return out;
}

const state = {
    motionResults: [],

    // yayCount: null,
    // nayCount: null,
    // //this is separate since
    // //for some uses will not send vote
    // //totals to the client
    // totalVotes: null,
    // passed: null
};

const mutations = {
    /**
     * Stores a new motion result
     * @param state
     * @param resultObject
     */
    addMotionResultToStore: (state, resultObject) => {
        // window.console.log(resultObject);
        let preExisting = getById(state.motionResults, resultObject.motionId)

        if (!isReadyToRock(preExisting)) {
            state.motionResults.push(resultObject)
        }
    },

    /**
     * Empties the list of results. Used when changing
     * meetings / elections
     * @param state
     */
    clearMotionResults: (state) => {
        state.motionResults = [];
    },


    deleteMotionResult: (state, resultObject) => {
        _.remove(state.motionResults, function (result) {
            return result.motionId === resultObject.motionId;
        });
    },

    /**
     * Updates a property on the result object
     * @param state
     * @param prop
     * @param val
     */
    setMotionResultProp: (state, {object, updateProp, updateVal}) => {
        Vue.set(object, updateProp, updateVal);
    },

    //
    // setNayCount: (state, payload) => {
    //     Vue.set(state, 'nayCount', payload);
    // },
    //
    //
    // setYayCount: (state, payload) => {
    //     Vue.set(state, 'yayCount', payload);
    // },
    // setPassed: (state, payload) => {
    //     Vue.set(state, 'passed', payload);
    // },
    // setTotalVotes: (state, payload) => {
    //     Vue.set(state, 'totalVotes', payload);
    // },
};

const actions = {

    /**
     * Gets the motion from the server
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @returns {Promise<unknown>}
     */
    loadMotionCounts({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {

            let url = routes.results.getCounts(motion.id);

            return Vue.axios.get(url)
                .then((response) => {
                    let existing = getters.getMotionResultObject(motion);
                    if (isReadyToRock(existing)) {
                        //we update
                        _.forEach(createUpdatePayloadsFromResponse(existing, response), (payload) => {
                            commit('setMotionResultProp', payload);
                        });
                    } else {
                        let result = new MotionResult(response.data);
                        commit('addMotionResultToStore', result);
                    }

                    // let results = response.data;
                    // commit('setYayCount', results.yayCount);
                    // commit('setNayCount', results.nayCount);

                    resolve()
                });
        }));
    },

    /**
     * Loads whether the motion passed and it's total vote count from the server.
     *
     * Pass false to setGlobalState when loading the results for past motions.
     *
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @param setGlobalState
     * @returns {Promise<unknown>}
     */
    loadMotionResults({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {

            let url = routes.results.getResults(motion.id);

            return Vue.axios.get(url)
                .then((response) => {
                    let existing = getters.getMotionResultObject(motion);
                    if (isReadyToRock(existing)) {
                        //we update
                        _.forEach(createUpdatePayloadsFromResponse(existing, response), (payload) => {
                            commit('setMotionResultProp', payload);
                        });
                    } else {
                        let result = new MotionResult(response.data);
                        commit('addMotionResultToStore', result);
                    }

                    // let results = response.data;
                    // commit('setPassed', results.passed);
                    // commit('setTotalVotes', results.totalVotes);

                    return resolve();
                });
        }));
    },


    /**
     * Used at start up to load results for all completed motions in the meeting.
     *
     * NB, only loads results. Does not load vote counts
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @returns {Promise<unknown>}
     */
    loadResultsForAllMeetingMotions({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            _.forEach(getters.getStoredMotions, (motion) => {
                if (motion.isComplete) {
                    dispatch('loadMotionResults', motion);
                }
            });

            //NB, not checking that all requests complete
            resolve();

        }));
    }

};

const getters = {

    getMotionNayCount: (state, getters) => (motion) => {
        let obj = getters.getMotionResultObject(motion);

        if (isReadyToRock(obj)) {
            return obj.nayCount;
        }
    },


    getMotionYayCount: (state, getters) => (motion) => {
        let obj = getters.getMotionResultObject(motion);
        if (isReadyToRock(obj)) {
            return obj.yayCount;
        }
    },

    getMotionPassed: (state, getters) => (motion) => {
        let obj = getters.getMotionResultObject(motion);
        if (isReadyToRock(obj)) {
            return obj.passed;
        }
    },

    getMotionResultObject: (state) => (motion) => {
        let motionId = idify(motion);
        return getById(state.motionResults, motionId);
    },

    getMotionTotalVoteCount: (state, getters) => (motion) => {
        let obj = getters.getMotionResultObject(motion);
        if (isReadyToRock(obj)) {
            return obj.totalVotes;
        }
//        return state.totalVotes;
        // return state.yayCount + state.nayCount;
    },

    // //--------------------- deprecated
    // getNayCount: (state) => {
    //     return state.nayCount;
    // },
    //
    // getYayCount: (state) => {
    //     return state.yayCount;
    // },
    //
    // getPassed: (state) => {
    //     return state.passed;
    // },
    // getTotalVoteCount: (state) => {
    //     return state.totalVotes;
    //     // return state.yayCount + state.nayCount;
    // }
};


export default {
    actions,
    getters,
    mutations,
    state,
}
