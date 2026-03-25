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

import Admin from './elections.admin';
import Navigation from './elections.navigation';
import Results from './elections.results';
import Setup from './elections.setup';
import Startup from './elections.startup';
import Votes from './elections.votes';

// import importCandidatesFromFile from './candidateFileImporter';

const state = {
    ...Admin.state,
    ...Navigation.state,
    ...Results.state,
    ...Setup.state,
    ...Startup.state,
    ...Votes.state,

    /**
     * People who have been nominated for offices
     */
    candidates: [],

};

const mutations = {
    ...Admin.mutations,
    ...Navigation.mutations,
    ...Results.mutations,
    ...Setup.mutations,
    ...Startup.mutations,
    ...Votes.mutations,


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

    setCandidateProp: (state, {id, updateProp, updateVal}) => {
        // window.console.log(updateProp, updateVal);
        let currentMotion = getById(state.candidates, id);

        currentMotion[updateProp] = updateVal;
        // Vue.set(currentMotion, updateProp, updateVal);

    },

    removeCandidate: (state, candidateObject) => {
        _.remove(state.candidates, function (candidate) {
            return candidate.id === candidateObject.id;
        });
    },


};


const actions = {
    //Import all the actions which affect people
    ...a,

    //Import actions used to move between offices etc
    ...Admin.actions,
    ...Navigation.actions,
    ...Results.actions,
    ...Setup.actions,
    ...Startup.actions,
    ...Votes.actions,


    addWriteInCandidateToOfficeElection({dispatch, commit, getters}, {first_name, last_name, info, motionId}) {
        let data = {first_name: first_name, last_name: last_name, info: info, is_write_in: true};

        let url = routes.election.addWriteIn(motionId);

        return new Promise(((resolve, reject) => {

            return axios.post(url, data)
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


};

const getters = {
    ...Admin.getters,
    ...Navigation.getters,
    ...Results.getters,
    ...Setup.getters,
    ...Startup.getters,
    ...Votes.getters,

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
     * Returns the subset of motions for the current
     * election which represent office elections.
     * @param state
     * @param getters
     * @returns {string[]}
     */
    getElectionOffices: (state, getters) => {
        let motions = getters.getMotions;
        return _.filter(motions, (m) => {
            return m.type !== 'proposition';
        });
    },

    /**
     * Returns the subset of motions for the current election
     * which represent propositions
     * @param state
     * @param getters
     * @returns {string[]}
     */
    getElectionPropositions: (state, getters) => {
        let motions = getters.getMotions;
        return _.filter(motions, (m) => {
            return m.type === 'proposition';
        });
    },


    getWriteInCandidatesForCurrentOffice: (state, getters) => {
        let motion = getters.getActiveMotion;

        return state.candidates.filter(function (c) {
            return c.motion_id === motion.id && c.isWriteIn;
        })

    },

    getUnvotedOffices: (state, getters) => {
        let motions = getters.getMotions;
        return motions.filter((motion) => {
            return !_.includes(getters.getMotionIdsUserVotedUpon, motion.id);
        });
    },

    getCandidateById: (state) => (candidateId) => {
        return state.candidates.filter((c) => {
            return c.id === candidateId;
        });
    },

    /**
     * Returns true if all offices in the current election
     * have been voted upon. False otherwise
     */
    isElectionComplete: (state, getters) => {
        let unvoted = getters.getUnvotedOffices;

        return unvoted.length === 0;
    },


};

export default {
    actions,
    getters,
    mutations,
    state,
}
