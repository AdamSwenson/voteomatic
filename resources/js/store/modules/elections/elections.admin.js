/**
 * Things used by chair or admin
 * beyond setting the thing up
 * @type {{}}
 */
import {idify} from "../../../utilities/object.utilities";

import Election from "../../../models/Election";
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

    /**
     * Makes it possible for voters to vote
     * @param dispatch
     * @param commit
     * @param getters
     * @param meeting
     * @returns {Promise<unknown>}
     */
    enableElectionVoting({dispatch, commit, getters}, meeting) {
        return new Promise(((resolve, reject) => {
            let meetingId = idify(meeting);
            let url = routes.election.admin.startVoting(meetingId);

            return Vue.axios.post(url)
                .then((response) => {
                    //We will overwrite the original object because
                    //there may be several things that change when the election
                    //starts and don't want to tightly couple.
                    let e = new Election(response.data);

                    _.forEach(_.keys(meeting), (prop) => {
                        if (e[prop] !== meeting[prop]) {
                            let p = new Payload();
                            p.updateProp = prop;
                            p.updateVal = e[prop];
                            commit('setMeetingProp', p);
                        }
                    });
                    // commit('addMeetingToStore', e);
                    // commit('setMeeting', e);
                    return resolve();
                });
        }));
    },

    /**
     * Makes it no longer possible for anyone to vote
     * @param dispatch
     * @param commit
     * @param getters
     * @param meeting
     * @returns {Promise<unknown>}
     */
    closeElectionVoting({dispatch, commit, getters}, meeting) {
        return new Promise(((resolve, reject) => {
            let meetingId = idify(meeting);
            let url = routes.election.admin.stopVoting(meetingId);

            return Vue.axios.post(url)
                .then((response) => {
                    //We will overwrite the original object because
                    //there may be several things that change when the election
                    //starts and don't want to tightly couple.
                    let e = new Election(response.data);
                    _.forEach(_.keys(meeting), (prop) => {
                        if (e[prop] !== meeting[prop]) {
                            let p = new Payload();
                            p.updateProp = prop;
                            p.updateVal = e[prop];
                            commit('setMeetingProp', p);
                        }
                    });
                    return resolve();
                });
        }));
    },

    /**
     * After the election has closed, allows voters generally to view results
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param meeting
     * @returns {Promise<unknown>}
     */
    releaseElectionResults({dispatch, commit, getters}, meeting) {
        return new Promise(((resolve, reject) => {
            let meetingId = idify(meeting);
            let url = routes.election.admin.releaseResults(meetingId);

            return Vue.axios.post(url)
                .then((response) => {
                    //We will overwrite the original object because
                    //there may be several things that change when the election
                    //results are released and don't want to tightly couple.
                    let e = new Election(response.data);

                    _.forEach(_.keys(meeting), (prop) => {
                        if (e[prop] !== meeting[prop]) {
                            let p = new Payload();
                            p.updateProp = prop;
                            p.updateVal = e[prop];
                            commit('setMeetingProp', p);
                        }
                    });

                    return resolve();
                });

        }));
    },

    /**
     * After the election has closed, prevents anyone except the chair/admin
     * from viewing  the election results
     * @param dispatch
     * @param commit
     * @param getters
     * @param meeting
     * @returns {Promise<unknown>}
     */
    hideElectionResults({dispatch, commit, getters}, meeting) {
        return new Promise(((resolve, reject) => {
            let meetingId = idify(meeting);
            let url = routes.election.admin.hideResults(meetingId);

            return Vue.axios.post(url)
                .then((response) => {
                    //We will overwrite the original object because
                    //there may be several things that change when the election
                    //results are hidden and don't want to tightly couple.
                    let e = new Election(response.data);

                    _.forEach(_.keys(meeting), (prop) => {
                        if (e[prop] !== meeting[prop]) {
                            let p = new Payload();
                            p.updateProp = prop;
                            p.updateVal = e[prop];
                            commit('setMeetingProp', p);
                        }
                    });
                    return resolve();
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
