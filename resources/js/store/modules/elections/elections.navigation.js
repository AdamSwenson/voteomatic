import {idify} from "../../../utilities/object.utilities";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import ElectionCard from "../../../components/election/voting/election-card";
import VotingCompleteCard from "../../../components/election/voter/voting-complete-card";
import VotingInstructionsCard from "../../../components/election/voter/voting-instructions-card";
import SummarySubmitCard from "../../../components/election/voter/summary-submit-card";
import PropositionVoteCard from "../../../components/election/voting/proposition-vote-card";

const state = {
    //things: []


    shownCard: 'instructions',

    // showSummarySubmitCard: false,

    // showInstructionsCard: true
};

const mutations = {
    /*
    *   addThing: (state, thing) => {
    *        state.things.push(thing);
    *    }
    */

    setShownCard: (state, cardName) => {
        state.shownCard = cardName;
    },

    showSummarySubmitCard: (state) => {
        state.shownCard = 'summary'
        // state.showSummarySubmitCard = true;
    },

    // hideSummarySubmitCard: (state) => {
    //     state.showSummarySubmitCard = false;
    // },

    showInstructionsCard: (state) => {
        state.shownCard = 'instructions';
        // state.showInstructionsCard = true;
    },

    // hideInstructionsCard: (state) => {
    //     state.showInstructionsCard = false;
    // },

    showPropositionVoteCard : (state) => {
   state.shownCard = 'proposition';
    },

    showVotingCard: (state) => {
    state.shownCard = 'election';
    },

    showVotingCompleteCard : (state) => {
    state.shownCard = 'complete';
    }

};


const actions = {


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
                //If there are no more unvoted offices
                //go to the summary card
                commit('showSummarySubmitCard');
                return resolve();
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

            //If we are at the max index, then the next button takes
            //us to the summary card
            //zero indexed so should work
            if (nxtIdx === numMotions) {
                //go to the summary card
                commit('showSummarySubmitCard');
                return resolve();
            }

            //Otherwise we go to next by index
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
            //Kludge so that if we are on the summary submit card and want to go back,
            //we will go to the max indexed motion.
            let summaryCardIdx = _.size(getters.getMotions);
            let currentIdx = getters.isSummarySubmitCardVisible ? summaryCardIdx : getters.getMotions.indexOf(getters.getActiveMotion);

            let prevIdx = currentIdx - 1;
            window.console.log('setting previous office. idx: ', prevIdx);

            if (prevIdx < 0) {
                return resolve();
            }

            let prevOffice = getters.getMotionByIndex(prevIdx);

            dispatch('setOfficeForVoting', prevOffice).then(() => {
                //the action should hide the summary card but just in case
                commit('showVotingCard');
                // commit('hideSummarySubmitCard');
                return resolve();
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
     * This hides the instruction and summary submit cards
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

            if(motion.type === 'proposition'){
                commit('showPropositionVoteCard');
            }else{
                commit('showVotingCard');
            }
            // commit('hideSummarySubmitCard');
            // commit('hideInstructionsCard');

            commit('setMotion', motion);
            // dispatch('setCurrentMotion', {
            //     meetingId: getters.getActiveMeeting.id,
            //     motionId: motionId
            // }).then(() => {

            // dispatch('loadElectionCandidates', motionId).then(() => {
                return resolve();
                // });
            // });
        }));

    },


    /*
    *    doThing({dispatch, commit, getters}, thingParam) {
    *        return new Promise(((resolve, reject) => {
    *        }));
    *    },
    */
};

/**
 *
 *    getThingViaId: (state) => (thingId) => {
 *        return state.things.filter(function (c) {
 *            return c.thing_id === thingId;
 *        })
 *    },
 *
 *
 *    getThing: (state, getters) => {}
 */
const getters = {
    isInstructionsCardVisible: (state) => {
        return state.shownCard === 'instructions';
        // return state.showInstructionsCard;
    },

    isSummarySubmitCardVisible: (state) => {
        return state.shownCard === 'summary';
        // return state.showSummarySubmitCard;
    },

    isCompleteCardShown: (state) => {
    return state.shownCard === 'complete';
        },
    /**
     * Returns the master dict of cards
     * which can be shown on the main election page
     *
     * @param state
     * @returns {any}
     */
    getShowableCards: (state) => {
        return {
            //Allows user to select candidates
            'election': ElectionCard,
            //Tells the user they are not allowed to vote
            'complete': VotingCompleteCard,
            //Tells the user how to vote
            'instructions': VotingInstructionsCard,
            //Voting on a proposition
            'proposition' : PropositionVoteCard,
            //User submits their selections
            'summary': SummarySubmitCard,

        }
    },



    /**
     * Returns the appropriate component.
     * NB, actually returns component not just name
     *
     * @param state
     * @param getters
     * @returns {*}
     */
    getShownCard : (state, getters) => {
        let c = {
            //Allows user to select candidates
            'election': ElectionCard,
            //Tells the user they are not allowed to vote. Allows to see receipts
            //if available
            'complete': VotingCompleteCard,
            //Tells the user how to vote
            'instructions': VotingInstructionsCard,
            //Voting on a proposition
            'proposition' : PropositionVoteCard,
            //User submits their selections
            'summary': SummarySubmitCard,
        }

    return c[state.shownCard];
    }

};

export default {
    actions,
    getters,
    mutations,
    state,
}
