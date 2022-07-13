import * as routes from "../../../routes";
import {idify} from "../../../utilities/object.utilities";
import Motion from "../../../models/Motion";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import Payload from "../../../models/Payload";
import Proposition from "../../../models/Proposition";
import officeFileImporter from "./officeFileImporter";

const state = {
    //things: []
    /**
     * Used during election setup. Holds pool members. I.e., people associated
     * with a motion id, but who are not yet
     * candidates.
     */
    candidatePool: [],
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


    clearPool: (state) => {
        state.candidatePool = [];
    },


};


const actions = {
    ...officeFileImporter,

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
                    //NB, this will catch all errors, including ones not having to do
                    //with the server request. If things are going weird with no obvious error
                    //displays, this may be why. (Guess why the console log call is here...)
                    window.console.log(error);
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
            seconded: true,
            type : 'election'
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
                    //NB, this will catch all errors, including ones not having to do
                    //with the server request. If things are going weird with no obvious error
                    //displays, this may be why. (Guess why the console log call is here...)
                    window.console.log(error);
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
     * Creates a new Proposition objects as the draftMotion
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    initializeDraftProposition({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            let motion = new Proposition({});
            commit('setDraftMotion', motion);

            // //We set it as a resolution since that will allow html display
            // let p = Payload.factory({
            //     'updateProp': 'is_resolution',
            //     'updateVal': true
            // });
            // dispatch('updateDraftMotion', p);
            //
            // //Needs to be a proposition
            // let p2 = Payload.factory({
            //     'updateProp': 'type',
            //     'updateVal': 'proposition'
            // });
            // dispatch('updateDraftMotion', p2);

            return resolve();
            // });

        }));
    },

    /**
     * Sends the draft proposition to the server and adds the
     * newly created object to store, and sets as current
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    createPropositionFromDraft({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            let meeting = getters.getActiveMeeting;

            dispatch('createMotionFromDraft').then((response) => {

                let p = new Proposition(response.data);

                //clear draft motion and hide the window.
                commit('clearDraftMotion');

                commit('addMotionToStore', p);

                let pl = {meetingId: meeting.id, motionId: p.id};

                return dispatch('setCurrentMotion', pl).then(() => {
                    return resolve(p);
                });
            });
        }));

    }
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
