import {isReadyToRock} from "../../../utilities/readiness.utilities";
import * as routes from "../../../routes";
import {idify} from "../../../utilities/object.utilities";
import Vote from "../../../models/Vote";

const state = {

    selectedCandidates: [],


    writeInCandidates: [],

    /**
     * Holds vote objects representing the user's selections.
     * We use a dictionary since there can only be one vote
     * per proposition
     */
    propositionVotes: {}


};

const mutations = {


    addCandidateToSelected: (state, candidateObject) => {
        state.selectedCandidates.push(candidateObject);
    },

    removeCandidateFromSelected: (state, candidateObject) => {
        // window.console.log('remove', candidateObject, state.selectedCandidates.indexOf(candidateObject));
        let idx = state.selectedCandidates.indexOf(candidateObject);
        state.selectedCandidates.splice(idx, 1);
        // _.remove(state.selectedCandidates, function (candidate) {
        //     return candidate.id === candidateObject.id;
        // });
    },

    addPropositionVote: (state, voteObject) => {
        state.propositionVotes[voteObject.motionId] = voteObject;
    },

    removePropositionVote: (state, motion) => {
        let motionId = idify(motion);
        state.propositionVotes[motionId] = null;

    },

    addWriteIn: (state, candidateObject) => {
        state.writeInCandidates.push(candidateObject);
    },


};


const actions = {

    /**
     * This sends all the selected candidates to the server
     * and records the vote for the  office.
     *
     * This does not operate on propositions
     *
     * If motion is null or undefined, uses current active motion
     * @param dispatch
     * @param commit
     * @param getters
     * @param motionId
     * @param candidateId
     * @returns {Promise<unknown>}
     */
    castElectionVote({dispatch, commit, getters}, motion) {

        let motionId = isReadyToRock(motion) ? motion.id : getters.getActiveMotion.id;
        window.console.log('saving votes for motion ', motionId);
        let url = routes.election.recordVote(motionId);

        //These are required by the route
        let data = {
            candidateIds: [],
            writeIns: []
        };

        return new Promise(((resolve, reject) => {

            _.forEach(getters.getSelectedCandidatesForMotion(motion), (candidate) => {
                data.candidateIds.push(candidate.id);
            });

            let me = this;

            return Vue.axios.post(url, data)
                .then((response) => {

                    console.log(response.data);

                    // commit('addReceipt', {
                    //     motionId: motionId,
                    //     receipt: response.data.receipt
                    // });
                    //
                    // //Add it to the already voted list
                    // commit('addVotedUponMotion', motionId);

                    let voteObject = new Vote({
                        motionId: motionId,
                        id: response.data.id,
                        receipt: response.data.receipt
                    })
                    // //NB, this is kosher since we haven't saved the object to state yet.
                    // voteObject.receipt = response.data.receipt;
                    // voteObject.id = response.data.id;

                    //Add the motion to the list of motions the user has voted upon
                    commit('addVotedUponMotion', voteObject.motionId);

                    //Store the receipt for the user. These are done separately since
                    //the voted upon list is used to restrict what a user may do and
                    //is populated every time the page loads.
                    //The castVote is used to store receipts in a store which
                    // will empty every time the page loads
                    commit('addCastVote', voteObject);

                    resolve();

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
     * Sends selections for all offices and all propositions
     * to the server
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    castAllElectionVotes({dispatch, commit, getters}) {
        // let offices = getters.getMotions;

        return new Promise(((resolve, reject) => {
            let offices = getters.getElectionOffices;
            _.forEach(offices, (motion) => {
                dispatch('castElectionVote', motion);
            });

            let propositions = getters.getElectionPropositions;
            _.forEach(propositions, (motion) => {
                let voteObj = getters.getPropositionVoteForMotion(motion);

                //Doing this in VOT-126 so that can use the OG castMotionVote
                // which assumes the vote object hasn't been stored to state yet.
                let voteObj2 = new Vote(voteObj);
                window.console.log('recording ', voteObj2);
                dispatch('castMotionVote', voteObj2);
            });

            //Prevent from accessing votes and show receipts if present
            commit('showVotingCompleteCard');

            resolve();

            // _.forEach(getters.getSelectedCandidatesForMotion, (candidate) => {
            //     data.candidateIds.push(candidate.id);
            // });
            //
            // let me = this;
            //
            // return Vue.axios.post(url, data)
            //     .then((response) => {
            //
            //         console.log(response.data);
            //
            //         commit('addReceipt', {
            //             motionId: motionId,
            //             receipt: response.data.receipt
            //         });
            //
            //         //Add it to the already voted list
            //         commit('addVotedUponMotion', motionId);
            //
            //         return resolve();
            //     }).catch(function (error) {
            //         // error handling
            //         if (error.response) {
            //             dispatch('showServerProvidedMessage', error.response.data);
            //             return reject(error);
            //         }
            //     });
        }));


    },


    /**
     * Sends selections for all offices and all propositions
     * where the user has made a selection
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    castElectionVotesForSelections({dispatch, commit, getters}) {
        // let offices = getters.getMotions;

        return new Promise(((resolve, reject) => {
            let offices = getters.getElectionOffices;
            _.forEach(offices, (motion) => {
                let selections = getters.getSelectedCandidatesForMotion(motion);
                if (selections.length > 0) {
                    dispatch('castElectionVote', motion);
                }
            });

            let propositions = getters.getElectionPropositions;
            _.forEach(propositions, (motion) => {
                let voteObj = getters.getPropositionVoteForMotion(motion);
                if (isReadyToRock(voteObj)) {

                    //Doing this in VOT-126 so that can use the OG castMotionVote
                    // which assumes the vote object hasn't been stored to state yet.
                    let voteObj2 = new Vote(voteObj);
                    window.console.log('recording ', voteObj2);
                    dispatch('castMotionVote', voteObj2);
                }

            });

            //Prevent from accessing votes and show receipts if present
            commit('showVotingCompleteCard');

            resolve();

            // _.forEach(getters.getSelectedCandidatesForMotion, (candidate) => {
            //     data.candidateIds.push(candidate.id);
            // });
            //
            // let me = this;
            //
            // return Vue.axios.post(url, data)
            //     .then((response) => {
            //
            //         console.log(response.data);
            //
            //         commit('addReceipt', {
            //             motionId: motionId,
            //             receipt: response.data.receipt
            //         });
            //
            //         //Add it to the already voted list
            //         commit('addVotedUponMotion', motionId);
            //
            //         return resolve();
            //     }).catch(function (error) {
            //         // error handling
            //         if (error.response) {
            //             dispatch('showServerProvidedMessage', error.response.data);
            //             return reject(error);
            //         }
            //     });
        }));


    },


    unselectCandidate({dispatch, commit, getters}, candidateObject) {

        return new Promise(((resolve, reject) => {

            commit('removeCandidateFromSelected', candidateObject);
            // commit('setOverSelectionWarning');

            resolve();


        }));
    },

    selectCandidate({dispatch, commit, getters}, candidateObject) {

        return new Promise(((resolve, reject) => {

            commit('addCandidateToSelected', candidateObject);
            // commit('setOverSelectionWarning');

            resolve();

        }));
    },

    storePropositionVote({dispatch, commit, getters}, voteObject) {
        return new Promise(((resolve, reject) => {

            commit('addPropositionVote', voteObject);
            resolve();

        }));
    }


};

const getters = {

    /**
     * Checks that all offices are free of errors such as overselection
     *
     * @param state
     * @param getters
     */
    isBallotErrorFree: (state, getters) => {
        try {
            //Check for over selection
            _.forEach(getters.getMotions, (motion) => {
                // window.console.log('error free', motion, getters.showOverSelectionWarningForMotion(motion));
                if (getters.showOverSelectionWarningForMotion(motion)) throw new Error('overselection ');
            });

            //Other error checks

            return true;
        } catch (e) {
            window.console.log(e);
            return false;
        }
    },

    /**
     * Returns true if the user has
     * voted on all offices and propositions; false otherwise
     * @param state
     * @param getters
     */
    isVotingComplete: (state, getters) => {
        let votedIds = getters.getMotionIdsUserVotedUpon;
        let motionIds = []

        //don't return yes if haven't loaded
        if(votedIds.length === 0 || motionIds.length ===  0){
            return false;
        }

        _.forEach(getters.getMotions, (motion) => {
            motionIds.push(motion.id);
        });

        return _.difference(motionIds, votedIds).length === 0;

    },

    getOfficesWithErrors: (state, getters) => {
        let problems = [];
        _.forEach(getters.getMotions, (motion) => {
            // window.console.log('error free', motion, getters.showOverSelectionWarningForMotion(motion));
            if (getters.showOverSelectionWarningForMotion(motion)) {
                problems.push(motion);
            }
            //other error checks
        });
        return problems;
    },


    getMaxWinners: (state, getters) => {
        let motion = getters.getActiveMotion;
        // window.console.log('gmw', motion);
        if (isReadyToRock(motion)) {
            return motion.max_winners;
        }

    },

    getPropositionVoteForMotion: (state, getters) => (motion) => {
        let motionId = idify(motion);
        return state.propositionVotes[motionId];
    },

    getAllSelectedCandidates: (state) => {
        return state.selectedCandidates;
    },

    /**
     * Returns the candidates selected by the user.
     *
     * If motion is null, gets them for the ative motion
     *
     * @param state
     * @param getters
     * @returns {function(*=): string[]}
     */
    getSelectedCandidatesForMotion: (state, getters) => (motion) => {
        // window.console.log('gc'. motion);
        motion = isReadyToRock(motion) ? motion : getters.getActiveMotion;
        // window.console.log('taco');
        // window.console.log('gca', motion);
        return _.filter(getters.getAllSelectedCandidates, (candidate) => {
            return candidate.motion_id === motion.id;
        });

    },

    getSelectedCandidatesForActiveMotion: (state, getters) => {

        let motion = getters.getActiveMotion;

        return _.filter(getters.getAllSelectedCandidates, (candidate) => {
            return candidate.motion_id === motion.id;
        });

    },

    /**
     * Returns true if the candidate is amongst those
     * the voter has selected for the currently active office
     *
     * @param state
     * @param getters
     * @returns boolean
     */
    isCandidateSelectedInActiveMotion: (state, getters) => (candidate) => {
        let selected = getters.getSelectedCandidatesForActiveMotion;

        let f = _.filter(selected, (s) => {
            if (s.isIdentical(candidate)) return s
        });
        return f.length > 0;
    },

    showOverSelectionWarningForActiveMotion: (state, getters) => {
        // window.console.log('osw in ', motion);
        let motion = getters.getActiveMotion;
        if (!isReadyToRock(motion)) return false;
        // window.console.log('osw', motion);
        let numberSelected = getters.getSelectedCandidatesForMotion(motion).length;
        return numberSelected > motion.max_winners;
    },


    showOverSelectionWarningForMotion: (state, getters) => (motion) => {
        // window.console.log('osw in ', motion);
        // motion = isReadyToRock(motion) ? motion : getters.getActiveMotion;
        if (!isReadyToRock(motion)) return false;
        // window.console.log('osw', motion);
        let numberSelected = getters.getSelectedCandidatesForMotion(motion).length;
        return numberSelected > motion.max_winners;
    },

    /**
     * Returns true if the user has selected more than 0 but less than the maximum
     * number of candidates for an office
     * @param state
     * @param getters
     * @returns {function(*=): boolean}
     */
    showUnderSelectionWarningForMotion: (state, getters) => (motion) => {
        let numberSelected = getters.getSelectedCandidatesForMotion(motion).length;
        return numberSelected > 0 && numberSelected < motion.max_winners;
        },

    getWriteInIndex: (state) => (name) => {
        return state.writeIns.indexOf(name);
    }

};

export default {
    actions,
    getters,
    mutations,
    state,
}
