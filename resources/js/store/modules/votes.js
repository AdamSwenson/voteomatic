import * as routes from "../../routes";
import Vote from "../../models/Vote";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import {idify, getById} from "../../utilities/object.utilities";
import Message from "../../models/Message";

const state = {
    // selectedCandidates: [],

    // showOverSelectionWarning: false,

    // writeInCandidates: [],

    castVotes: [],


};

const mutations = {
    addCastVote: (state, voteObject) => {

        state.castVotes.push(voteObject);

    },

    // addCandidateToSelected: (state, candidateObject) => {
    //     state.selectedCandidates.push(candidateObject);
    // },
    //
    // removeCandidateFromSelected: (state, candidateObject) => {
    //     // window.console.log('remove', candidateObject, state.selectedCandidates.indexOf(candidateObject));
    //     let idx = state.selectedCandidates.indexOf(candidateObject);
    //     state.selectedCandidates.splice(idx, 1);
    //     // _.remove(state.selectedCandidates, function (candidate) {
    //     //     return candidate.id === candidateObject.id;
    //     // });
    // },
    //
    // /**
    //  * Compares the number selected to the max winners prop
    //  * of the motion and sets the show warning value accordingly
    //  *
    //  * @param state
    //  * @param motion
    //  */
    // setOverSelectionWarning: (state, motion) => {
    //     let numberSelected = state.selectedCandidates.length;
    //     state.showOverSelectionWarning = numberSelected < motion.max_winners;
    //     window.console.log('overselection', state.showOverSelectionWarning, numberSelected);
    // },


    //
    // addWriteIn: (state, candidateObject) => {
    //     state.writeInCandidates.push(candidateObject);
    // },

    // updateWriteIn : (state, {index, name}) => {
    //     let
    // state.writeInCandidates[index] = name;
    //     // Vue.set(state, )
    //
    // }


};


const actions = {


    /**
     * For regular votes on motions (i.e., not election votes), this
     * sends the vote to the server. It then updates the vote object
     * and stores it locally so the user can verify the receipt oif they choose.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @param vote yay|nay
     * @returns {Promise<unknown>}
     */
    castMotionVote({dispatch, commit, getters}, voteObject) {
        return new Promise((resolve, reject) => {
            let url = routes.votes.recordVote(voteObject.motionId);
            let data = {
                motionId: voteObject.motionId,
                vote: voteObject.voteServerString,
            };

            return Vue.axios.post(url, data)
                .then((response) => {
                    console.log(response.data);
                    //NB, this is kosher since we haven't saved the object to state yet.
                    voteObject.receipt = response.data.receipt;
                    voteObject.id = response.data.id;

                    //Add the motion to the list of motions the user has voted upon
                    commit('addVotedUponMotion', voteObject.motionId);

                    //Store the receipt for the user. These are done separately since
                    //the voted upon list is used to restrict what a user may do and
                    //is populated every time the page loads.
                    //The castVote is used to store receipts in a store which
                    // will empty every time the page loads
                    commit('addCastVote', voteObject);

                    resolve();
                })
                .catch(function (error) {

                    // error handling
                    if (error.response) {

                        dispatch('showServerProvidedMessage', error.response.data);

                        window.console.log(error);
                        //
                        // //todo Error messaging
                        // let message = Message.makeFromTemplate('voteRecordingError');
                        // //todo Add server generated message
                        // // message.messageText = message.messageText += error.response.message;
                        // dispatch('showMessage', message);
                        //
                        // // The request was made and the server responded with a status code
                        // // that falls out of the range of 2xx
                        // console.log(error.response.data);
                        // console.log(error.response.status);
                        // if (error.response.status === 501) {
                        //  //   me.voteRecorded = true;
                        //    // me.showButtons = false;
                        // }

                    }
                    throw error;
                    // reject();
                });

        });

        // }

        // let url = routes.votes.recordVote(motion.id);
        // let data = {
        //     motionId: motion.id,
        //     vote: voteType,
        // };
        //
        // return new Promise((resolve, reject) => {
        //     let me = this;
        //     return Vue.axios.post(url, data)
        //         .then((response) => {
        //             console.log(response.data);
        //             me.vote = new Vote(response.data.isYay, response.data.receipt, response.data.id);
        //             me.voteRecorded = true;
        //             me.showButtons = false;
        //             //todo once receives notification that vote has been recorded, should set voteRecorded to true so inputs can be disabled.
        //
        //             me.$store.commit('addVotedUponMotion', me.motion.id);
        //             resolve();
        //         })
        //         .catch(function (error) {
        //             // error handling
        //             if (error.response) {
        //                 // The request was made and the server responded with a status code
        //                 // that falls out of the range of 2xx
        //                 console.log(error.response.data);
        //                 console.log(error.response.status);
        //                 if (error.response.status === 501) {
        //                     me.voteRecorded = true;
        //                     me.showButtons = false;
        //                 }
        //
        //             }
        //             // reject();
        //         });
        //
        // });
    },

    //
    // /**
    //  * This sends all the selected candidates to the server
    //  * and records the vote for the  office.
    //  * If motion is null or undefined, uses current active motion
    //  * @param dispatch
    //  * @param commit
    //  * @param getters
    //  * @param motionId
    //  * @param candidateId
    //  * @returns {Promise<unknown>}
    //  */
    // castElectionVote({dispatch, commit, getters}, motion) {
    //
    //     let motionId = isReadyToRock(motion) ? motion.id : getters.getActiveMotion.id;
    //
    //     let url = routes.election.recordVote(motionId);
    //
    //     //These are required by the route
    //     let data = {
    //         candidateIds: [],
    //         writeIns: []
    //     };
    //
    //     return new Promise(((resolve, reject) => {
    //
    //         _.forEach(getters.getSelectedCandidatesForMotion, (candidate) => {
    //             data.candidateIds.push(candidate.id);
    //         });
    //
    //         let me = this;
    //
    //         return Vue.axios.post(url, data)
    //             .then((response) => {
    //
    //                 console.log(response.data);
    //
    //                 commit('addReceipt', {
    //                     motionId: motionId,
    //                     receipt: response.data.receipt
    //                 });
    //
    //                 //Add it to the already voted list
    //                 commit('addVotedUponMotion', motionId);
    //
    //                 return resolve();
    //             }).catch(function (error) {
    //                 // error handling
    //                 if (error.response) {
    //                     dispatch('showServerProvidedMessage', error.response.data);
    //                     return reject(error);
    //                 }
    //             });
    //     }));
    //
    //
    // },
    //
    // /**
    //  * Sends selections for all offices with selections to the server
    //  *
    //  * @param dispatch
    //  * @param commit
    //  * @param getters
    //  * @returns {Promise<unknown>}
    //  */
    // castAllElectionVotes({dispatch, commit, getters}) {
    //     let motions = getters.getMotions;
    //
    //     return new Promise(((resolve, reject) => {
    //
    //         _.forEach(motions, (motion) => {
    //             dispatch('castElectionVote', motion);
    //         })
    //
    //         // _.forEach(getters.getSelectedCandidatesForMotion, (candidate) => {
    //         //     data.candidateIds.push(candidate.id);
    //         // });
    //         //
    //         // let me = this;
    //         //
    //         // return Vue.axios.post(url, data)
    //         //     .then((response) => {
    //         //
    //         //         console.log(response.data);
    //         //
    //         //         commit('addReceipt', {
    //         //             motionId: motionId,
    //         //             receipt: response.data.receipt
    //         //         });
    //         //
    //         //         //Add it to the already voted list
    //         //         commit('addVotedUponMotion', motionId);
    //         //
    //         //         return resolve();
    //         //     }).catch(function (error) {
    //         //         // error handling
    //         //         if (error.response) {
    //         //             dispatch('showServerProvidedMessage', error.response.data);
    //         //             return reject(error);
    //         //         }
    //         //     });
    //     }));
    //
    //
    // },
    //
    //
    // unselectCandidate({dispatch, commit, getters}, candidateObject) {
    //
    //     return new Promise(((resolve, reject) => {
    //
    //         commit('removeCandidateFromSelected', candidateObject);
    //         // commit('setOverSelectionWarning');
    //
    //         resolve();
    //
    //
    //     }));
    // },
    //
    // selectCandidate({dispatch, commit, getters}, candidateObject) {
    //
    //     return new Promise(((resolve, reject) => {
    //
    //         commit('addCandidateToSelected', candidateObject);
    //         // commit('setOverSelectionWarning');
    //
    //         resolve();
    //
    //     }));
    // }
};


const getters = {
    /**
     * Returns all stored votes the user has cast
     * @param state
     * @param getters
     * @returns {[]|{getVotedMotions: function(*): string}|{getVotedMotions: function(*): string}|*}
     */
    getUsersCastVotes: (state, getters) => {
        return state.castVotes;
    },

    getCastVoteForMotion: (state, getters) => (motion) => {
        let motionId = idify(motion);
        let v = _.filter(getters.getUsersCastVotes, (vote) => {
            return vote.motionId === motionId;
        });
        return v[0];

    },

    //
    // areAdditionalSelectionsAllowed: (state, getters) => {
    //     let motion = getters.getActiveMotion;
    //     let numberSelected = getters.getAllSelectedCandidates.length;
    //     if (isReadyToRock(motion.max_winners)) {
    //         return numberSelected < motion.max_winners;
    //     }
    //     //todo What if not set? Could we end up here?
    // },

    // /**
    //  * Checks that all offices are free of errors such as overselection
    //  *
    //  * @param state
    //  * @param getters
    //  */
    // isBallotErrorFree: (state, getters) => {
    //     try {
    //         //Check for over selection
    //         _.forEach(getters.getMotions, (motion) => {
    //             // window.console.log('error free', motion, getters.showOverSelectionWarningForMotion(motion));
    //             if (getters.showOverSelectionWarningForMotion(motion)) throw new Error('overselection ');
    //         });
    //
    //         //Other error checks
    //
    //         return true;
    //     } catch (e) {
    //         window.console.log(e);
    //         return false;
    //     }
    // },
    //
    // getOfficesWithErrors: (state, getters) => {
    //     let problems = [];
    //     _.forEach(getters.getMotions, (motion) => {
    //         // window.console.log('error free', motion, getters.showOverSelectionWarningForMotion(motion));
    //         if (getters.showOverSelectionWarningForMotion(motion)) {
    //             problems.push(motion);
    //         }
    //         //other error checks
    //     });
    //     return problems;
    // },


    //
    // getMaxWinners: (state, getters) => {
    //     let motion = getters.getActiveMotion;
    //     // window.console.log('gmw', motion);
    //     if (isReadyToRock(motion)) {
    //         return motion.max_winners;
    //     }
    //
    // },
    //
    // getAllSelectedCandidates: (state) => {
    //     return state.selectedCandidates;
    // },
    //
    // /**
    //  * Returns the candidates selected by the user.
    //  *
    //  * If motion is null, gets them for the ative motion
    //  *
    //  * @param state
    //  * @param getters
    //  * @returns {function(*=): string[]}
    //  */
    // getSelectedCandidatesForMotion: (state, getters) => (motion) => {
    //     // window.console.log('gc'. motion);
    //     motion = isReadyToRock(motion) ? motion : getters.getActiveMotion;
    //     // window.console.log('taco');
    //     // window.console.log('gca', motion);
    //     return _.filter(getters.getAllSelectedCandidates, (candidate) => {
    //         return candidate.motion_id === motion.id;
    //     });
    //
    // },
    //
    // getSelectedCandidatesForActiveMotion: (state, getters) => {
    //
    //     let motion = getters.getActiveMotion;
    //
    //     return _.filter(getters.getAllSelectedCandidates, (candidate) => {
    //         return candidate.motion_id === motion.id;
    //     });
    //
    // },
    //
    // showOverSelectionWarningForActiveMotion: (state, getters) => {
    //     // window.console.log('osw in ', motion);
    //     let motion = getters.getActiveMotion;
    //     if (!isReadyToRock(motion)) return false;
    //     // window.console.log('osw', motion);
    //     let numberSelected = getters.getSelectedCandidatesForMotion(motion).length;
    //     return numberSelected > motion.max_winners;
    // },
    //
    //
    // showOverSelectionWarningForMotion: (state, getters) => (motion) => {
    //     // window.console.log('osw in ', motion);
    //     // motion = isReadyToRock(motion) ? motion : getters.getActiveMotion;
    //     if (!isReadyToRock(motion)) return false;
    //     // window.console.log('osw', motion);
    //     let numberSelected = getters.getSelectedCandidatesForMotion(motion).length;
    //     return numberSelected > motion.max_winners;
    // },
    //
    // getWriteInIndex: (state) => (name) => {
    //     return state.writeIns.indexOf(name);
    // }


};

export default {
    actions,
    getters,
    mutations,
    state,
}

