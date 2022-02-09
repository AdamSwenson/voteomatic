import {idify} from "../../../utilities/object.utilities";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
const state = {
    //things: []

    showSummarySubmitCard: false
};

const mutations = {
    /*
    *   addThing: (state, thing) => {
    *        state.things.push(thing);
    *    }
    */

    showSummarySubmitCard: (state) => {
        state.showSummarySubmitCard = true;

    },

    hideSummarySubmitCard: (state) => {
        state.showSummarySubmitCard = false;
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
                commit('hideSummarySubmitCard');
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
            commit('hideSummarySubmitCard');

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


    isSummarySubmitCardVisible: (state) => {
        return state.showSummarySubmitCard;
    },

};

export default {
    actions,
    getters,
    mutations,
    state,
}
