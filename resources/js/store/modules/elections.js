import * as routes from "../../routes";
import Meeting from "../../models/Meeting";
import Candidate from "../../models/Candidate";
import {getById} from "../../utilities/object.utilities";

const state = {
    candidates: []
};

const mutations = {

    addCandidateToStore: (state, candidateObject) => {
        state.candidates.push(candidateObject);
    },

    setCandidateProp: (state, {id, updateProp, updateVal}) => {
        // window.console.log(updateProp, updateVal);
        let currentMotion = getById(state.candidates, id);

        Vue.set(currentMotion, updateProp, updateVal);

    },

};


const actions = {
    addOfficialCandidateToOfficeElection({dispatch, commit, getters}, {name, info, motionId}) {
        let data = {name: name, info: info, is_write_in: false};

        let url = routes.election.candidates(motionId);

        return new Promise(((resolve, reject) => {

            return Vue.axios.post(url, data).then((response) => {
                let candidate = new Candidate(response.data);
                commit('addCandidateToStore', candidate);

                resolve();
            });
        }));


    },

    addWriteInCandidateToOfficeElection({dispatch, commit, getters}, {name, info, motionId}) {
        let data = {name: name, info: info, is_write_in: true};

        let url = routes.election.candidates(motionId);

        return new Promise(((resolve, reject) => {

            return Vue.axios.post(url, data).then((response) => {
                let candidate = new Candidate(response.data);
                commit('addCandidateToStore', candidate);

                //No reason to make the user separately select a write in
                commit('addCandidateToSelected', candidate);

                resolve();
            });
        }));


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
    ,

    /**
     * Sets the next elected office as the current motion
     *
     * While this is technically operating on motions, the
     * way we get from one motion to another is very different
     * for elections. Thus handling this here.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motionId
     * @returns {Promise<unknown>}
     */
    nextOffice({dispatch, commit, getters}, motionId) {
        return new Promise(((resolve, reject) => {

            let idx = getters.getMotions.indexOf(getters.getActiveMotion);
            let unvotedOffices = getters.getUnvotedOffices;

            window.console.log('pending', unvotedOffices);

            if (unvotedOffices.length > 0) {
                let toSet = unvotedOffices[0];
                dispatch('setCurrentMotion', {
                    meetingId: getters.getActiveMeeting.id,
                    motionId: toSet.id
                }).then(() => {
                    dispatch('loadElectionCandidates', toSet.id).then(() => {
                        return resolve();

                    });


                });
            }


        }));

    },

    updateCandidate({dispatch, commit, getters}, payload) {
        let motionId = getters.getActiveMotion.id;
        // let url = routes.election.candidates(motionId, payload.id);
        let url = routes.election.candidates(motionId, payload.id);

        let data = {};
        data[payload.updateProp] = payload.updateVal;

        return new Promise(((resolve, reject) => {

                return Vue.axios.post(url, data).then((response) => {

                    commit('setCandidateProp', payload);
                    resolve();

                });


            })
        );

    },

};

const getters = {

    /**
     * A motion represents a elected position which is
     * decided during an election (i.e., a meeting).
     *
     * This does NOT return write in candidates
     *
     * @param state
     * @returns {function(*): *}
     */
    getCandidatesForOffice: (state) => (motion) => {
        return state.candidates.filter(function (c) {
            return c.motion_id === motion.id && c.isWriteIn !== true;
        })

    },

    getWriteInCandidatesForCurrentOffice: (state, getters) => {
        let motion = getters.getActiveMotion;

        return state.candidates.filter(function (c) {
            return c.motion_id === motion.id && c.isWriteIn;
        })

        //
        // let office = getters.getActiveMotion;
        // let candidates = getters.getCandidatesForOffice(office);
        // window.console.log('k', candidates);
        // return candidates.filter((c) => {
        //     return c.isWriteIn === true;
        // })
    },

    getUnvotedOffices: (state, getters) => {
        let motions = getters.getMotions;
        return motions.filter((motion) => {
            return !_.includes(getters.getMotionIdsUserVotedUpon, motion.id);
        });
    }

};

export default {
    actions,
    getters,
    mutations,
    state,
}
