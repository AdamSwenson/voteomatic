
import readCandidatesFromFile from './candidateFileImporter';
import * as routes from "../../../routes";
import PoolMember from "../../../models/PoolMember";
import Candidate from "../../../models/Candidate";
import Payload from "../../../models/Payload";
import {idify} from "../../../utilities/object.utilities";

/**
 * All actions that involve pool members and candidates
 * @type {{readPeopleFromFile: function({state: *, dispatch: *, commit: *, getters: *}, *=): Promise<unknown>}}
 */
const actions ={
    ...readCandidatesFromFile,

    /**
     * Creates a person who can be a pool member or a candidate
     * NB, the pool member object provided is just used to organize the
     * relevant properties. It doesn't have any of the ids.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param poolMember
     */
    createPerson({dispatch, commit, getters}, poolMember) {
        return new Promise(((resolve, reject) => {
            let url = routes.election.resource.people();

            return Vue.axios.post(url, poolMember)
                .then((response) => {
                    let person = new PoolMember(response.data);

                    return resolve(person);

                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));

    },

    createPoolFromFile({dispatch, commit, getters}, file) {
        let motion = getters.getActiveMotion;

        return new Promise(((resolve, reject) => {
            dispatch('readPeopleFromFile', file).then((people) => {
                window.console.log('people', people);
                //Will have a list of people objects
                _.forEach(people, (p) => {
                    p.motion_id = motion.id;
                    dispatch('createPerson', p).then((p2) => {
                        dispatch('addPersonToPool', {person: p2, motionId: motion.id})
                            .then(() => {
                            //dev VOT-169 shouldn't this be after the loop?
                                return resolve();
                            });
                    });
                });

            });

        }));
    },

    /**
     * Edit properties of a pool member ---makes the changes on the
     * underlying person object
     * @param dispatch
     * @param commit
     * @param getters
     * @param payload
     */
    editPerson({dispatch, commit, getters}, payload) {
//todo
    },

    /**
     * Takes a pool member and makes them a candidate for
     * the office. That is, they will now be someone whom voters
     * can vote for.
     *
     * NB, while the candidate object may exist on the client as
     * part of the candidatePool, it may not yet exist on the server
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param candidate
     * @returns {Promise<unknown>}
     */
    addCandidate({dispatch, commit, getters}, poolMember) {

        let url = routes.election.nominatePoolMember(poolMember.id);
        // let url = routes.election.resource.candidate();

        return new Promise(((resolve, reject) => {

            return Vue.axios.post(url)
                .then((response) => {
                    let candidate = new Candidate(response.data);
                    commit('addCandidateToStore', candidate);
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
     * Adds a new field which will display for all candidates
     * in the election
     * @param dispatch
     * @param commit
     * @param getters
     * @param fieldName
     */
    addCandidateField({dispatch, commit, getters}, fieldName){
        let election = getters.getActiveMeeting;
        let fields = _.concat(election.candidateFields, [fieldName]);
        let payload = Payload.factory({
            updateProp : 'info',
            updateVal : {'candidateFields' : fields}
        });

        dispatch('updateMeeting', payload)
    },

    deleteCandidateField({dispatch, commit, getters}, fieldName){
        let election = getters.getActiveMeeting;
        let fields = _.filter(election.candidateFields, (c) => {
        return c != fieldName;
        });
        let payload = Payload.factory({
            updateProp : 'info',
            updateVal : {'candidateFields' : fields}
        });

        dispatch('updateMeeting', payload)
    },


    /**
     * Takes and existing person and makes the a potential
     * candidate for a given office
     * @param dispatch
     * @param commit
     * @param getters
     * @param person
     * @param motionId
     * @returns {Promise<unknown>}
     */
    addPersonToPool({dispatch, commit, getters}, {person, motionId}) {

        let url = routes.election.addToPool(motionId, person.id);

        return new Promise(((resolve, reject) => {

            return Vue.axios.post(url)
                .then((response) => {
                    //we receive a pool member object with the correct motion id
                    let member = new PoolMember(response.data);

                    commit('addCandidateToPool', member);
                    return resolve(member);

                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));


    },


    /**
     * Load those who are eligible to be nominated for this
     * office (i.e., pool members)
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motionId
     * @returns {Promise<unknown>}
     */
    loadCandidatePool({dispatch, commit, getters}, motionId) {
        motionId = idify(motionId);
        let url = routes.election.getPool(motionId);
        return new Promise(((resolve, reject) => {
            // window.console.log(url);

            return Vue.axios.get(url)
                .then((response) => {
                    commit('clearPool');
                    _.forEach(response.data, (d) => {
                        let candidate = new PoolMember(d);
                        commit('addCandidateToPool', candidate);
                    });

                    return resolve();

                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
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

            return Vue.axios.get(url)
                .then((response) => {
                    // commit('clearCandidates');
                    _.forEach(response.data, (d) => {

                        // window.console.log('loadElectionCandidates', d);

                        let candidate = new Candidate(d);

                        // window.console.log('obj', candidate);
                        commit('addCandidateToStore', candidate);

                    });

                    return resolve();

                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });

        }));

    },


    loadAllOfficeCandidates({dispatch, commit, getters}) {
let motions = getters.getMotions;

        return new Promise(((resolve, reject) => {
            _.forEach(motions, (motion) => {
            dispatch('loadElectionCandidates', motion.id);
            });
            return resolve();

        }));

    },


    /**
     * Loads all candidates for all offices the user hasn't
     * yet voted upon
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    loadUnvotedOfficeCandidates({dispatch, commit, getters}) {
        let motions = getters.getUnvotedOffices;
        return new Promise(((resolve, reject) => {
            _.forEach(motions, (motion) => {
                dispatch('loadElectionCandidates', motion.id);
            });
            return resolve();

        }));

    },


};


export { actions as default}
