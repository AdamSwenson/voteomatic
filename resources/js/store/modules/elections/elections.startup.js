import * as routes from "../../../routes";
import EventObjectFactory from "../../../models/EventObjectFactory";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

/**
 * Initialization and other actions etc which
 * get called on startup
 *
 * Created by adam on 2020-07-30.
 */
//
// import Motion from "../../models/Motion";
// import Payload from "../../models/Payload";


const state = {};

const mutations = {};

const actions = {
    //
    // /**
    //  * Parses the things set in window.startData
    //  * and sets them in store
    //  */
    // loadElectionFromPageData({dispatch, commit, getters}) {
    //     return new Promise((resolve, reject) => {
    //         let data = window.startData;
    //         window.console.log('startup', 'start data', 25, data);
    //         let electionId = null;
    //         if (!_.isUndefined(data.election_id)) {
    //             electionId = data.election_id;
    //         }
    //         //prefer the id if it is set directly on page
    //         else if (!_.isUndefined(data.motion.election_id)) {
    //             electionId = data.motion.election_id;
    //         }
    //
    //         if (_.isNull(electionId)) {
    //             console.log('No election id in page data');
    //             return resolve();
    //         }
    //
    //
    //         console.log("Loading election from server from id in page data")
    //         dispatch('loadElection', electionId).then(function () {
    //             return resolve();
    //         });
    //
    //     });
    // },


    /**
     * Loads the meeting object (election) directly from the
     * data included in the page.
     *
     * We do this to speed things up so the user won't see a blank page for too long
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    loadElectionFromPageData({dispatch, commit, getters},) {

        return new Promise(((resolve, reject) => {
            let meetingData = window.startData.meeting;
            // window.console.log('meeting d', meetingData);
            if (!isReadyToRock(meetingData)) return reject('No meeting data on page');

            //Will create either a  Election object
            let meeting = EventObjectFactory.make(meetingData);
            commit('addMeetingToStore', meeting);
            commit('setMeeting', meeting);
            return resolve()
        }));
    },

    initializeElection({dispatch, commit, getters}) {
        return new Promise((resolve, reject) => {
            window.console.log('startup', 'Initializing election from page data');

            dispatch('loadIsAdminFromPageData').then(function () {

                dispatch('loadElectionFromPageData').then(function () {

                    let meeting = getters.getActiveMeeting;

                    dispatch('navigateToAppropriateLocation', meeting);

                    dispatch('loadMotionFromPageData').then(function () {

                        dispatch('initializeElectionListeners');

                        //get existing motions for meeting
                        dispatch('loadMotionsForMeeting', meeting.id).then(function () {

                            //get motions which have already been handled
                            dispatch('loadMotionsUserHasVotedUpon', meeting.id).then(function () {
                                //dev This would be a good place to decide whether to send to
                                // a has voted page
                                dispatch('loadUnvotedOfficeCandidates').then(() => {
                                    return resolve();
                                });

                                //
                                // dispatch('loadResultsForAllMeetingMotions').then(function () {
                                // });
                                //
                                // dispatch('loadMotionTypesAndTemplates').then(function () {
                                // });

                            });

                        });

                    });

                });

            });
        });
    },

    initializeElectionListeners({dispatch, commit, getters}) {
        let meeting = getters.getActiveMeeting;
        let channel = `meeting.${meeting.id}`;
        // Echo.private(channel)
        //     .listen("GeneralNotification", (e) => {
        //         dispatch('handlePusherGeneralNotification', e);
        //     })
        //     .listen("MotionSeekingSecond", (e) => {
        //         window.console.log('Received broadcast event meeting', e);
        //         dispatch('handleMotionSeekingSecondMessage', e);
        //     })
        //     .listen("MotionSeconded", (e) => {
        //         window.console.log('Received broadcast event meeting', e);
        //         //Switches to the motion which has now been approved and seconded
        //         dispatch('handleMotionSecondedMessage', e);
        //     })
        //     .listen("NoSecondObtained", (e) => {
        //         window.console.log('Received broadcast event meeting', e);
        //         dispatch('handleNoSecondObtainedMessage', e);
        //     })
        //     .listen('MotionMarkedOutOfOrder', (e) => {
        //         window.console.log('Received broadcast event meeting', e);
        //         dispatch('handleMotionMarkedOutOfOrderMessage', e);
        //     })
        //     .listen('NewCurrentMotionSet', (e) => {
        //         //In some cases the chair may select a motion from the
        //         //home page. When that heppens we need to force everyone onto
        //         //a new motion
        //         dispatch('handleNewCurrentMotionSetMessage', e);
        //     })
        //     .listen('VotingOnMotionOpened', (e) => {
        //         dispatch('handleVotingOnMotionOpenedMessage', e);
        //     });
        //
        window.console.log('Election listeners initialized for ', channel);
        //
        if (getters.getIsAdmin) {
            let chairChannel = `chair.${meeting.id}`;
            //     Echo.private(chairChannel)
            //         .listen('MotionNeedingApproval', (e) => {
            //             window.console.log('Received chair broadcast', chairChannel, e);
            //             dispatch('handleMotionNeedingApprovalMessage', e);
            //         })
            //         .listen('MotionVoteCast', (e) => {
            //             window.console.log('Received chair broadcast', chairChannel, e);
            //             dispatch('handleCastVoteMessage', e);
            //         });
            //
            window.console.log('Chair listeners initialized for ', chairChannel);
        }
    },


};

const getters = {};


export default {
    actions,
    getters,
    mutations,
    state,
}
