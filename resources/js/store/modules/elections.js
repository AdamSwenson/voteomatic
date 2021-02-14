import * as routes from "../../routes";
import Meeting from "../../models/Meeting";
import Candidate from "../../models/Candidate";

const state = {
    candidates: []
};

const mutations = {

    addCandidateToStore: (state, candidateObject) => {
        state.candidates.push(candidateObject);
    }
};


const actions = {
    addCandidateToOfficeElection({dispatch, commit, getters}, motionId) {

    },



    // loadOfficesForElection({dispatch, commit, getters}, meetingId) {
    //     let url = routes.election.getOffices(meetingId);
    //
    //     return new Promise(((resolve, reject) => {
    //
    //         return Vue.axios.get(url).then((response) => {
    //
    //             _.forEach(response.data, (d) => {
    //                 // window.console.log('loadAllMeetings', d);
    //                 let motion = new Motion(d);
    //                 commit('addMotionToStore', motion);
    //
    //             });
    //
    //             resolve();
    //
    //         });
    //
    //     }));
    //
    //
    // },

    /**
     * Get candidates for an office
     *
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    loadElectionCandidates({dispatch, commit, getters}, motionId) {
        let url = routes.election.candidates(motionId);

            return new Promise(((resolve, reject) => {

                return Vue.axios.get(url).then((response) => {

                    _.forEach(response.data, (d) => {

                        window.console.log('loadElectionCandidates', d);

                        let candidate = new Candidate(d);

                        window.console.log('obj', candidate);
                        commit('addCandidateToStore', candidate);

                    });

                    return resolve();

                });

            }));



    }


};

const getters = {

    /**
     * A motion represents a elected position which is
     * decided during an election (i.e., a meeting)
     *
     * @param state
     * @returns {function(*): *}
     */
    getCandidatesForOffice: (state) => (motion) => {
        return state.candidates.filter(function (c) {
            return c.motion_id === motion.id;
        })

    }

};

export default {
    actions,
    getters,
    mutations,
    state,
}
