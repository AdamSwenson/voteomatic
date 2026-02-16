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

        _.remove(state.castVotes, function (v) {
            return v.motionId === voteObject.motionId;
        });


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

            return axios.post(url, data)
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


    },

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


};

export default {
    actions,
    getters,
    mutations,
    state,
}

