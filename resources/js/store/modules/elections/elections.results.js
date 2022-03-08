import * as routes from "../../../routes";
import CandidateResult from "../../../models/CandidateResult";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
const state = {

    electionResults: [],

};

const mutations = {


    addResults: (state, results) => {

        //dev Add filter to deal with VOT-128
        let matches = state.electionResults.filter((r) => {
            return r.candidateId === results.candidateId;
        });
        if(matches.length === 0 ) state.electionResults.push(results);
    },

};


const actions = {
    loadResultsForOffice({dispatch, commit, getters}, motionId) {
        let url = routes.election.getResults(motionId);

        return new Promise(((resolve, reject) => {
            return Vue.axios.get(url)
                .then((response) => {
                    _.forEach(response.data, (d) => {
                        let r = new CandidateResult(d);
                        commit('addResults', r);
                    });

                    resolve();
                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
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
const getters = {
    getOfficeResults: (state) => (motion) => {
        return state.electionResults.filter((r) => {
            return r.motionId === motion.id;
        });

        // return state.electionResults[motionId];
    },

    /**
     * This takes into account how many winners there can be
     * for an office.
     *
     * @param state
     * @returns {function(*)}
     */
    getOfficeWinners: (state, getters) => (motion) => {
        let results = getters.getOfficeResults(motion);

        //dev should probably do this on server

        //todo check for ties

    },

};

export default {
    actions,
    getters,
    mutations,
    state,
}
