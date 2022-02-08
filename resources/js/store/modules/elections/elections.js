import * as routes from "../../../routes";
import Meeting from "../../../models/Meeting";
import Candidate from "../../../models/Candidate";
import {getById} from "../../../utilities/object.utilities";
import CandidateResult from "../../../models/CandidateResult";
import Election from "../../../models/Election";
import {idify} from "../../../utilities/object.utilities";
import Motion from "../../../models/Motion";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import PoolMember from "../../../models/PoolMember";

// import {actions as iactions} from './candidateFileImporter';
// import {importCandidatesFromFile} from './candidateFileImporter';
import a from './elections.people.actions';
import Payload from "../../../models/Payload";

// import importCandidatesFromFile from './candidateFileImporter';

const state = {
    /**
     * People who have been nominated for offices
     */
    candidates: [],

    /**
     * Used during election setup. Holds pool members. I.e., people associated
     * with a motion id, but who are not yet
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

        //See if the person is already in candidates
        //NB, we can't just filter duplicate objects
        let r = state.candidates.filter(function (c) {
            if (c.isIdentical(candidateObject)) return c
        });
        if (r.length === 0) {
            state.candidates.push(candidateObject);
        }


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
    //Import all the actions which affect people
    ...a,


    addWriteInCandidateToOfficeElection({dispatch, commit, getters}, {first_name, last_name, info, motionId}) {
        let data = {first_name: first_name, last_name: last_name, info: info, is_write_in: true};

        let url = routes.election.addWriteIn(motionId);

        return new Promise(((resolve, reject) => {

            return Vue.axios.post(url, data)
                .then((response) => {
                    let candidate = new Candidate(response.data);
                    commit('addCandidateToStore', candidate);

                    //No reason to make the user separately select a write in
                    commit('addCandidateToSelected', candidate);

                    return resolve();
                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                        return reject(error);
                    }
                });
        }));


    },

    /**
     * Create an election event in which votes will be
     * cast for several offices. Election is equivalent to a meeting
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    createElection: function ({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            // let data = {name : name, date : date};
            let url = routes.election.resource.election()
            return Vue.axios.post(url)
                .then((response) => {

                    // dev Added in VOT-125 to deal with problem of still being on original meeting
                    //  NB, this opens the new meeting in a new window. Not sure how annoying that will be.
                    //  These changes parallel VOT-117
                    let url = routes.meetings.main(response.data.id);
                    dispatch('forceNavigationToUrl', url);

                    // dev Removed in VOT-125
                    // let meeting = new Election(response.data);
                    // commit('addMeetingToStore', meeting);
                    // commit('setMeeting', meeting);
                    // //now set to be in editing mode
                    //
                    // window.console.log('election created id: ', meeting.id);
                    // resolve()
                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));
    },

    /**
     * Creates a new elected office within the election.
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
            description: '',
            //Otherwise the controller will not send the office
            //when we ask for all motions
            seconded: true
        };

        return new Promise(((resolve, reject) => {

            return Vue.axios.post(url, data)
                .then((response) => {
                    let motion = new Motion(response.data);

                    commit('addMotionToStore', motion);
                    dispatch('setMotion', motion).then(() => {
                        dispatch('loadCandidatePool', motion).then((response) => {
                            resolve();
                        });
                    });


                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
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


    /**
     * Sets the next unvoted elected office as the current motion
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
    nextOffice({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {

            let idx = getters.getMotions.indexOf(getters.getActiveMotion);
            let unvotedOffices = getters.getUnvotedOffices;

            window.console.log('pending', unvotedOffices);

            if (unvotedOffices.length > 0) {
                let toSet = unvotedOffices[0];

                dispatch('setOfficeForVoting', toSet).then(() => {
                    return resolve();
                });

                     } else {
                // reject();
            }

        }));

    },


    /**
     * Sets the next elected office as the current motion, regardless
     * of whether it has been voted upon
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
    nextOfficeInStack({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {

            let currentIdx = getters.getMotions.indexOf(getters.getActiveMotion);
            let nxtIdx = currentIdx + 1;
            window.console.log('setting next office. idx: ', nxtIdx);

            let numMotions = _.size(getters.getMotions);

            //zero indexed so works
            if (nxtIdx === numMotions) {
                return resolve();
            }

            let nxtOffice = getters.getMotionByIndex(nxtIdx);

            dispatch('setOfficeForVoting', nxtOffice).then(() => {
                return resolve();
            });

        }));

    },

    /**
     * Sets the previous elected office (in stack) as the current motion
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
    previousOffice({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {

            let currentIdx = getters.getMotions.indexOf(getters.getActiveMotion);
            let prevIdx = currentIdx - 1;
            window.console.log('setting previous office. idx: ', prevIdx);

            if (prevIdx < 0) {
                return resolve();
            }

            let prevOffice = getters.getMotionByIndex(prevIdx);

            dispatch('setOfficeForVoting', prevOffice).then(() => {
                return resolve();
            });

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
        // let url = routes.election.candidates(motionId, payload.id);
        let url = routes.election.removeCandidate(candidate.id);

        return new Promise(((resolve, reject) => {

            return Vue.axios.delete(url)
                .then((response) => {

                    commit('removeCandidate', candidate);
                    resolve();

                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });

        }));

    },


    /**
     * Sets the specified office as the current motion for voting
     *
     * While this is technically operating on motions, the
     * way we get from one motion to another is very different
     * for elections. Thus handling this here.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion Motion or motion id
     * @returns {Promise<unknown>}
     */
    setOfficeForVoting({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            let motionId = idify(motion);

            window.console.log('setting office id ', motionId);
            commit('setMotion', motion);
            // dispatch('setCurrentMotion', {
            //     meetingId: getters.getActiveMeeting.id,
            //     motionId: motionId
            // }).then(() => {
            dispatch('loadElectionCandidates', motionId).then(() => {
                return resolve();
                // });
            });
        }));

    },

    // updateCandidate({dispatch, commit, getters}, payload) {
    //     let motionId = getters.getActiveMotion.id;
    //     // let url = routes.election.candidates(motionId, payload.id);
    //     let url = routes.election.resource.candidate(payload.id);
    //
    //     let data = {};
    //     data[payload.updateProp] = payload.updateVal;
    //
    //     return new Promise(((resolve, reject) => {
    //             return Vue.axios.patch(url, data).then((response) => {
    //                 commit('setCandidateProp', payload);
    //                 resolve();
    //             });
    //         })
    //     );
    //
    // },


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
     * Returns all potential nominees from the pool for the
     * provided motion
     * @param state
     * @returns {function(*): *[]}
     */
    getCandidatePoolForOffice: (state) => (motion) => {
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
        // window.console.log('ispac', officesMemberIsCandidate);
        if (!isReadyToRock(officesMemberIsCandidate) || officesMemberIsCandidate.length === 0) return false;

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
