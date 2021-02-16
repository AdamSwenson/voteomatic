import * as routes from "../../routes";
import Vote from "../../models/Vote";
import {isReadyToRock} from "../../utilities/readiness.utilities";

const state = {
    selectedCandidates: [],

    // showOverSelectionWarning: false,

    writeInCandidates : [],

    receipts: {}
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

    /**
     * Compares the number selected to the max winners prop
     * of the motion and sets the show warning value accordingly
     *
     * @param state
     * @param motion
     */
    setOverSelectionWarning: (state, motion) => {

        let numberSelected = state.selectedCandidates.length;
        state.showOverSelectionWarning = numberSelected < motion.max_winners;

    },

    addReceipt: (state, {motionId, receipt}) => {
        Vue.set(state.receipts, motionId, receipt);

    },

    addWriteIn : (state, candidateObject) => {
        state.writeInCandidates.push(candidateObject);
    },

    // updateWriteIn : (state, {index, name}) => {
    //     let
    // state.writeInCandidates[index] = name;
    //     // Vue.set(state, )
    //
    // }


};


const actions = {

    /**
     * DEV NOT READY FOR USE
     */
    castRegularMotionVote({dispatch, commit, getters}, motion) {

        let url = routes.votes.recordVote(motion.id);
        let data = {
            motionId: motion.id,
            vote: voteType,
        };

        return new Promise((resolve, reject) => {
            let me = this;
            return Vue.axios.post(url, data)
                .then((response) => {
                    console.log(response.data);
                    me.vote = new Vote(response.data.isYay, response.data.receipt, response.data.id);
                    me.voteRecorded = true;
                    me.showButtons = false;
                    //todo once receives notification that vote has been recorded, should set voteRecorded to true so inputs can be disabled.

                    me.$store.commit('addVotedUponMotion', me.motion.id);
                    resolve();
                })
                .catch(function (error) {
                    // error handling
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        console.log(error.response.data);
                        console.log(error.response.status);
                        if (error.response.status === 501) {
                            me.voteRecorded = true;
                            me.showButtons = false;
                        }

                    }
                    // reject();
                });

        });
    },


    /**
     * This sends all the selected candidates to the server
     * and records the vote for the  office
     * @param dispatch
     * @param commit
     * @param getters
     * @param motionId
     * @param candidateId
     * @returns {Promise<unknown>}
     */
    castElectionVote({dispatch, commit, getters}) {
        let motionId = getters.getActiveMotion.id;

        let url = routes.election.recordVote(motionId);

        //These are required by the route
        let data = {
            candidateIds: [],
            writeIns: []
        };

        return new Promise(((resolve, reject) => {

            _.forEach(getters.getSelectedCandidatesForMotion, (candidate) => {
                data.candidateIds.push(candidate.id);
            });

            let me = this;

            return Vue.axios.post(url, data)
                .then((response) => {

                    console.log(response.data);

                    commit('addReceipt', {
                        motionId: motionId,
                        receipt: response.data.receipt
                    });

                    //Add it to the already voted list
                    commit('addVotedUponMotion', motionId);

                    return resolve();
                });
        }));


    },

    unselectCandidate({dispatch, commit, getters}, candidateObject) {

        return new Promise(((resolve, reject) => {

            commit('removeCandidateFromSelected', candidateObject);
            resolve();

            // commit('setOverSelectionWarning');

        }));
    },

    selectCandidate({dispatch, commit, getters}, candidateObject) {

        return new Promise(((resolve, reject) => {

            commit('addCandidateToSelected', candidateObject);
            resolve();
            // commit('setOverSelectionWarning');

        }));
    }
};


const getters = {
    //
    // areAdditionalSelectionsAllowed: (state, getters) => {
    //     let motion = getters.getActiveMotion;
    //     let numberSelected = getters.getAllSelectedCandidates.length;
    //     if (isReadyToRock(motion.max_winners)) {
    //         return numberSelected < motion.max_winners;
    //     }
    //     //todo What if not set? Could we end up here?
    // },

    getMaxWinners: (state, getters) => {
        let motion = getters.getActiveMotion;
        // window.console.log('gmw', motion);
        if (isReadyToRock(motion)) {
            return motion.max_winners;
        }

    },

    getAllSelectedCandidates: (state) => {
        return state.selectedCandidates;
    },

    getSelectedCandidatesForMotion: (state, getters) => {
        let motion = getters.getActiveMotion;
        // window.co3nsole.log('taco');
        return _.filter(getters.getAllSelectedCandidates, (candidate) => {
            return candidate.motion_id === motion.id;
        });

    },

    showOverSelectionWarning: (state, getters) => {

        let motion = getters.getActiveMotion;
        if (!isReadyToRock(motion)) return false;
        // window.console.log('osw', motion);
        let numberSelected = getters.getSelectedCandidatesForMotion.length;
        return numberSelected > motion.max_winners;


        // state.showOverSelectionWarning = numberSelected < motion.max_winners;
        //
        //
        // return state.showOverSelectionWarning;
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

