import {idify} from "../../../utilities/object.utilities";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import ElectionCard from "../../../components/election/voting/election-card";
import VotingCompleteCard from "../../../components/election/unavailable/voting-complete-card";
import VotingInstructionsCard from "../../../components/election/voter/voting-instructions-card";
import SummarySubmitCard from "../../../components/election/voter/summary-submit-card";
import PropositionVoteCard from "../../../components/election/voting/proposition-vote-card";
import PrematureAccessCard from "../../../components/election/unavailable/premature-access-card";
import ResultsCard from "../../../components/election/results/election-results-card";
import ClosedCard from "../../../components/election/unavailable/election-closed-card";

import {router} from "../../../app";

const state = {

    /** What card should be shown when go to /election-home */
    shownHomeCard: 'instructions',

};

const mutations = {


    setShownCard: (state, cardName) => {
        state.shownHomeCard = cardName;
    },

    showClosedCard: (state) => {
        state.shownHomeCard = 'closed';
    },
    showSummarySubmitCard: (state) => {
        state.shownHomeCard = 'summary'
        // state.showSummarySubmitCard = true;
    },

    // hideSummarySubmitCard: (state) => {
    //     state.showSummarySubmitCard = false;
    // },

    showInstructionsCard: (state) => {
        state.shownHomeCard = 'instructions';
        // state.showInstructionsCard = true;
    },

    // hideInstructionsCard: (state) => {
    //     state.showInstructionsCard = false;
    // },

    showPropositionVoteCard: (state) => {
        state.shownHomeCard = 'proposition';
    },

    showVotingCard: (state) => {
        state.shownHomeCard = 'election';
    },

    showResultsCard: (state) => {
        state.shownHomeCard = 'results';
    },

    showVotingCompleteCard: (state) => {
        state.shownHomeCard = 'complete';
    },

    showPrematureCard: (state) => {
        state.shownHomeCard = 'premature';
    }

};


const actions = {

    /**
     * Pushes the election home tab to the router.
     *
     * Unlike the meeting navigation, this does not rely on the watchers
     * that are defined in NavigationMixin.
     *
     * dev If we need websocket action, add the watcher to NavigationMixin
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    forceNavigationToElectionHome({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {

            if (router.currentRoute.name !== 'election-home') {
                router.push('election-home');
            }
            resolve();
        }));
    },

    forceNavigationToElectionResults({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {

            if (router.currentRoute.name !== 'election-results') {
                router.push('election-results');
            }
            resolve();
        }));
    },


    /**
     * Makes the decision on where to go and sends there.
     * See the election lifecycle in the documentation for Meeting.php
     * for explanation.
     */
    navigateToAppropriateLocation({dispatch, commit, getters}, meeting) {
        return new Promise(((resolve, reject) => {
            if (!isReadyToRock(meeting)) return reject();

            if (getters.getIsAdmin) {
                dispatch('navigateToAppropriateLocationChair', meeting).then(() => {
                    return resolve();
                });
            } else {
                dispatch('navigateToAppropriateLocationRegularUser', meeting).then(() => {
                    return resolve();
                });
            }

            // //Election access has not yet been enabled by
            // //the chair
            // if (!meeting.isVotingAvailable && !meeting.isComplete) {
            //     commit('showPrematureCard');
            //     return resolve();
            // }
            //
            // //The election has ended
            // if (! meeting.isVotingAvailable && meeting.isComplete) {
            //     //Results are available
            //     if(meeting.isResultsAvailable) {
            //         commit('showResultsCard');
            //         return dispatch('forceNavigationToElectionResults').then(() => {
            //             return resolve();
            //         });
            //
            //
            //     }else{
            //         //No results available
            //         commit('showVotingCompleteCard');
            //         return resolve();
            //     }
            //
            // }
            //
            // //Voter has completed voting (The election could still be open for others)
            // //todo Determine if there are no available motions (offices/props) to vote upon
            // if(getters.isVotingComplete){
            //     commit('showVotingCompleteCard');
            // }
            //
            // //Voter can vote for stuff
            // dispatch('forceNavigationToElectionHome').then(() => {
            //     return resolve();
            // });

        }));
    },


    /**
     * Makes the decision on where to go and sends there.
     * See the election lifecycle in the documentation for Meeting.php
     * for explanation.
     */
    navigateToAppropriateLocationChair({dispatch, commit, getters}, meeting) {
        return new Promise(((resolve, reject) => {
            if (!isReadyToRock(meeting)) return reject();
            switch (meeting.phase) {

                case 'setup':
                    commit('setShownCard', 'setup');
                    break;

                case 'nominations':
                    commit('setShownCard', 'setup');
                    break;

                case 'voting':
                    commit('setShownCard', 'instructions');
                    break;

                case 'closed':
                    //chair can see results and administer
                    commit('setShownCard', 'admin');
                    break;

                case 'results':
                    commit('showResultsCard');
                    break;

                default:
            }
            dispatch('forceNavigationToElectionHome').then(() => {
return resolve();
            });
            //
            //
            //
            // switch (meeting.electionPhase) {
            //
            //     case 'setup':
            //         dispatch('forceNavigationToElectionHome').then(() => {
            //
            //         });
            //         break;
            //
            //     case 'nominations':
            //         dispatch('forceNavigationToElectionHome').then(() => {
            //
            //         });
            //         break;
            //
            //     case 'voting':
            //
            //         //Voter has completed voting (The election could still be open for others)
            //         //todo Determine if there are no available motions (offices/props) to vote upon
            //         if (getters.isVotingComplete) {
            //             commit('showVotingCompleteCard');
            //         }else{
            //             //Voter can vote for stuff
            //             dispatch('forceNavigationToElectionHome').then(() => {
            //
            //             });
            //         }
            //
            //         break;
            //
            //     case 'closed':
            //         //No results available
            //         commit('showVotingCompleteCard');
            //         break;
            //
            //     case 'results':
            //         commit('showResultsCard');
            //         return dispatch('forceNavigationToElectionResults').then(() => {
            //
            //         });
            //         break;
            //
            //     default:
            // }
            // return resolve();
            // //
            // // //Election access has not yet been enabled by
            // // //the chair
            // // if (!meeting.isVotingAvailable && !meeting.isComplete) {
            // //     commit('showPrematureCard');
            // //     return resolve();
            // // }
            // //
            // // //The election has ended
            // // if (!meeting.isVotingAvailable && meeting.isComplete) {
            // //     //Results are available
            // //     if (meeting.isResultsAvailable) {
            // //         commit('showResultsCard');
            // //         return dispatch('forceNavigationToElectionResults').then(() => {
            // //             return resolve();
            // //         });
            // //
            // //
            // //     } else {
            // //         //No results available
            // //         commit('showVotingCompleteCard');
            // //         return resolve();
            // //     }
            // //
            // // }
            // //
            // // //Voter has completed voting (The election could still be open for others)
            // // //todo Determine if there are no available motions (offices/props) to vote upon
            // // if (getters.isVotingComplete) {
            // //     commit('showVotingCompleteCard');
            // // }
            // //
            // // //Voter can vote for stuff
            // dispatch('forceNavigationToElectionHome').then(() => {
            //     return resolve();
            // });

        }));
    },


    /**
     * Makes the decision on where to go and sends there.
     * See the election lifecycle in the documentation for Meeting.php
     * for explanation.
     */
    navigateToAppropriateLocationRegularUser({dispatch, commit, getters}, meeting) {
        return new Promise(((resolve, reject) => {
            if (!isReadyToRock(meeting)) return reject();
            window.console.log(meeting.phase, 'ep');

            switch (meeting.phase) {

                case 'setup':
                    commit('showPrematureCard');
                    break;

                case 'nominations':
                    commit('showPrematureCard');

                    break;

                case 'voting':

                    //Voter has completed voting (The election could still be open for others)
                    //todo Determine if there are no available motions (offices/props) to vote upon
                    if (getters.isVotingComplete) {
                        commit('showVotingCompleteCard');
                    } else {
                        commit('showVotingCard');
                        //Voter can vote for stuff
                    }

                    break;

                case 'closed':
                    window.console.log('closed');
                    //No results available
                    commit('showClosedCard');
                    // dispatch('forceNavigationToElectionHome').then(() => {
                    // });
                    break;

                case 'results':
                    commit('showResultsCard');
                    // return dispatch('forceNavigationToElectionResults').then(() => {
                    //
                    // });
                    break;

                default:
            }

            return dispatch('forceNavigationToElectionHome').then(() => {
                return resolve();
            });

            //
            // //Election access has not yet been enabled by
            // //the chair
            // if (!meeting.isVotingAvailable && !meeting.isComplete) {
            //     commit('showPrematureCard');
            //     return resolve();
            // }
            //
            // //The election has ended
            // if (!meeting.isVotingAvailable && meeting.isComplete) {
            //     //Results are available
            //     if (meeting.isResultsAvailable) {
            //         commit('showResultsCard');
            //         return dispatch('forceNavigationToElectionResults').then(() => {
            //             return resolve();
            //         });
            //
            //
            //     } else {
            //         //No results available
            //         commit('showVotingCompleteCard');
            //         return resolve();
            //     }
            //
            // }
            //
            // //Voter has completed voting (The election could still be open for others)
            // //todo Determine if there are no available motions (offices/props) to vote upon
            // if (getters.isVotingComplete) {
            //     commit('showVotingCompleteCard');
            // }
            //
            // //Voter can vote for stuff
            // dispatch('forceNavigationToElectionHome').then(() => {
            //     return resolve();
            // });

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
                // commit('showVotingCard');
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

            if (motion.type === 'proposition') {
                commit('showPropositionVoteCard');
            } else {
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


};

const getters = {
    isInstructionsCardVisible: (state) => {
        return state.shownHomeCard === 'instructions';
        // return state.showInstructionsCard;
    },

    isSummarySubmitCardVisible: (state) => {
        return state.shownHomeCard === 'summary';
        // return state.showSummarySubmitCard;
    },

    isCompleteCardShown: (state) => {
        return state.shownHomeCard === 'complete';
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
            'proposition': PropositionVoteCard,
            //User submits their selections
            'summary': SummarySubmitCard,

        }
    },


    /**
     * Returns the appropriate component for election home.
     * This is for cards which occupy election-home
     * NB, actually returns component not just name
     *
     *
     * @param state
     * @param getters
     * @returns {*}
     */
    getShownHomeCard: (state, getters) => {
        let c = {
            //Election has ended but results are not available
            'closed': ClosedCard,
            //Allows user to select candidates
            'election': ElectionCard,
            //Tells the user they are not allowed to vote. Allows to see receipts
            //if available
            'complete': VotingCompleteCard,
            //Tells the user how to vote
            'instructions': VotingInstructionsCard,
            //Voting not yet allowed
            'premature': PrematureAccessCard,
            //Voting on a proposition
            'proposition': PropositionVoteCard,
            //Results of the election
            'results': ResultsCard,
            //User submits their selections
            'summary': SummarySubmitCard,
        }

        return c[state.shownHomeCard];
    }

};

export default {
    actions,
    getters,
    mutations,
    state,
}
