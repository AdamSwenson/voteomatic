/**
 * Things used by chair or admin
 * beyond setting the thing up
 * @type {{}}
 */
import {idify} from "../../../utilities/object.utilities";

import Election from "../../../models/Election";
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
                    commit('addMeetingToStore', e);
                    commit('setMeeting', e);
                    return resolve();
                });
        }));
    },

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
                    commit('addMeetingToStore', e);
                    commit('setMeeting', e);
                    return resolve();
                });
        }));
    },

    releaseResults({dispatch, commit, getters}, meeting) {
        return new Promise(((resolve, reject) => {
            let meetingId = idify(meeting);
            let url = routes.election.admin.releaseResults(meetingId);

            return Vue.axios.post(url)
                .then((response) => {
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
