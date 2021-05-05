import * as routes from "../../routes";
import Meeting from "../../models/Meeting";
import Candidate from "../../models/Candidate";
import {getById} from "../../utilities/object.utilities";
import Result from "../../models/Result";
import Election from "../../models/Election";
import {idify} from "../../utilities/object.utilities";
import Motion from "../../models/Motion";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import PoolMember from "../../models/PoolMember";

const state = {
    candidates: [],

    /**
     * Used during election setup. Holds candidate
     * objects who have a motion id, but they are not necessarily
     * candidates.
     */
    candidatePool: [],

    electionResults: [],


};

const mutations = {

    /**
     * Adds a potential candidate to the pool for an office
     * @param state
     * @param candidateObject
     */
    addCandidateToPool: (state, candidateObject) => {
        state.candidatePool.push(candidateObject);
    },

    addCandidateToStore: (state, candidateObject) => {
        //todo This attempt to filter duplicates doesn't work because the candidate objects have different ids
        window.console.log(getById(state.candidates, candidateObject.id));
        if (!isReadyToRock(getById(state.candidates, candidateObject.id))) {

            state.candidates.push(candidateObject);
        }

        // window.console.log(state.candidates.indexOf(candidateObject));
        // if(state.candidates.indexOf(candidateObject) === -1){
        //
        // }

    },

    clearCandidates: (state) => {
        state.candidates = [];
        window.console.log('candidates', state.candidates);
    },

    clearPool: (state) => {
        state.candidatePool = [];
    },

    setCandidateProp: (state, {id, updateProp, updateVal}) => {
        // window.console.log(updateProp, updateVal);
        let currentMotion = getById(state.candidates, id);

        Vue.set(currentMotion, updateProp, updateVal);

    },


    addResults: (state, results) => {
        state.electionResults.push(results);
        // Vue.set(state.electionResults, motionId, results);

    },

    removeCandidate: (state, candidateObject) => {
        _.remove(state.candidates, function (candidate) {
            return candidate.id === candidateObject.id;
        });

    }

};


const actions = {


    /**
     * NB, while the candidate object may exist on the client as
     * part of the candidatePool, it may not yet exist on the server
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param candidate
     * @returns {Promise<unknown>}
     */
    addPoolMemberToOfficeElection({dispatch, commit, getters}, poolMember) {

        let url = routes.election.nominatePoolMember(poolMember.id);
        // let url = routes.election.resource.candidate();

        return new Promise(((resolve, reject) => {

            return Vue.axios.post(url).then((response) => {
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

    createElection: function ({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            // let data = {name : name, date : date};
            let url = routes.election.resource.election()
            return Vue.axios.post(url)
                .then((response) => {
                    let meeting = new Election(response.data);
                    commit('addMeetingToStore', meeting);
                    commit('setMeeting', meeting);
                    //now set to be in editing mode

                    window.console.log('election created id: ', meeting.id);
                    resolve()
                });
        }));
    },

    /**
     * Creates a new elected office within the meeting.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param meeting Meeting object or meeting id
     * @returns {Promise<unknown>}
     */
    createOffice({dispatch, commit, getters}, meeting) {

        let url = routes.election.resource.office();

        let data = {
            meetingId: idify(meeting),
            //NB, an office is represented by a motion, hence we need to use
            //the expected keys even though it seems odd in this context
            content: '',
            description: ''
        };

        return new Promise(((resolve, reject) => {

            return Vue.axios.post(url, data).then((response) => {
                let motion = new Motion(response.data);

                commit('addMotionToStore', motion);
                commit('setMotion', motion);

                dispatch('loadCandidatePool', motion).then((response) => {
                    resolve();
                });

            });
        }));


    },

    /**
     * Alias for deleting motions which represent offices
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     */
    deleteOffice({dispatch, commit, getters}, motion) {
        dispatch('deleteMotion', motion);
    },


    loadResultsForOffice({dispatch, commit, getters}, motionId) {
        let url = routes.election.getResults(motionId);

        return new Promise(((resolve, reject) => {
            return Vue.axios.get(url).then((response) => {
                _.forEach(response.data, (d) => {
                    let r = new Result(d);
                    commit('addResults', r);
                });

                resolve();
            });
        }));
    },

    // loadAllElections({dispatch, commit, getters}){
    //     return new Promise(((resolve, reject) => {
    //
    //         //send to server
    //         let url = routes.election.resource.election();
    //         return Vue.axios.get(url)
    //             .then((response) => {
    //                 _.forEach(response.data, (d) => {
    //                     // window.console.log('loadAllMeetings', d);
    //                     let election = new Election(d);
    //                     commit('addMeetingToStore', election);
    //                     resolve()
    //                 });
    //             });
    //     }));
    // },

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


    loadCandidatePool({dispatch, commit, getters}, motionId) {
        motionId = idify(motionId);
        let url = routes.election.getPool(motionId);
        return new Promise(((resolve, reject) => {
            // window.console.log(url);

            return Vue.axios.get(url).then((response) => {
                commit('clearPool');
                _.forEach(response.data, (d) => {
                    let candidate = new PoolMember(d);
                    commit('addCandidateToPool', candidate);
                });

                return resolve();

            });

        }));

    },


    /**
     * Get candidates for an office. These are the people
     * who have been nominated.
     *
     * (The pool contains those eligible to be nominated)
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
                commit('clearCandidates');
                _.forEach(response.data, (d) => {

                    window.console.log('loadElectionCandidates', d);

                    let candidate = new Candidate(d);

                    // window.console.log('obj', candidate);
                    commit('addCandidateToStore', candidate);

                });

                return resolve();

            });

        }));

    },

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
            } else {
                // reject();
            }


        }));

    },


    /**
     * Makes the person no longer a candidate for the office
     * @param dispatch
     * @param commit
     * @param getters
     * @param payload
     * @returns {Promise<unknown>}
     */
    removeCandidate({dispatch, commit, getters}, candidate) {
        let motionId = getters.getActiveMotion.id;
        // let url = routes.election.candidates(motionId, payload.id);
        let url = routes.election.resource.candidate(candidate.id);

        return new Promise(((resolve, reject) => {

                return Vue.axios.delete(url).then((response) => {

                    commit('removeCandidate', candidate);
                    resolve();

                });


            })
        );

    },


    updateCandidate({dispatch, commit, getters}, payload) {
        let motionId = getters.getActiveMotion.id;
        // let url = routes.election.candidates(motionId, payload.id);
        let url = routes.election.resource.candidate(payload.id);

        let data = {};
        data[payload.updateProp] = payload.updateVal;

        return new Promise(((resolve, reject) => {
                return Vue.axios.patch(url, data).then((response) => {
                    commit('setCandidateProp', payload);
                    resolve();
                });
            })
        );

    },


};

const getters = {

    getCandidateByPersonId: (state) => (personId) => {
        return state.candidates.filter((c) => {
            return c.person_id === personId;
        });
    },

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
        let motionId = idify(motion);
        return state.candidates.filter(function (c) {
            return c.motion_id === motionId && c.isWriteIn !== true;
        })

    },

    /**
     * Returns all candidate objects from the pool for the
     * provided motion
     * @param state
     * @returns {function(*): *[]}
     */
    getCandidatePoolForOffice: (state) => (motion) => {
//         let candidateIds = [];
//         _.forEach(state.candidates, (c) => {
// candidateIds.push(c);
//         });
//     return
//         window.console.log('cl', candidateIds);
        return state.candidatePool.filter(function (c) {
            return c.motion_id === motion.id
        })

    },

    getElectionOffices: (state, getters) => (election) => {
        let motions = getters.getMotions;
        return _.filter(motions, (m) => {


        });

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
    },

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

    /**
     * Returns true if all offices in the current election
     * have been voted upon. False otherwise
     */
    isElectionComplete: (state, getters) => {
        let unvoted = getters.getUnvotedOffices;

        return unvoted.length === 0;
    },
    //
    // getVoteCounts: (state) => (motionId) => {
    // return state.electionResults[motionId].counts;
    // },
    //
    //
    // getVoteShares: (state) => (motionId) => {
    //     return state.electionResults[motionId].shares;
    // }

    /**
     * Returns false if a pool member is already a candidate
     *
     * @param state
     * @param getters
     */
    isPoolMemberACandidate: (state, getters) => (motion, poolMember) => {
        let officesMemberIsCandidate = getters.getCandidateByPersonId(poolMember.person_id);
        window.console.log('ispac', officesMemberIsCandidate);
        if(!isReadyToRock(officesMemberIsCandidate) || officesMemberIsCandidate.length === 0) return false;

        //Now we check to see if it is the same office. This shouldn't be needed
        //unless the candidates/pool don't get cleared. Thus keeping
        return _.forEach(officesMemberIsCandidate, (candidate) => {
            if (candidate.motion_id === poolMember.motion_id) return true;
        });
        return false;

    }

};

export default {
    actions,
    getters,
    mutations,
    state,
}
