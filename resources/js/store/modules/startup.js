/**
 * Initialization and other actions etc which
 * get called on startup
 *
 * Created by adam on 2020-07-30.
 */

import Motion from "../../models/Motion";
import Payload from "../../models/Payload";

const state = {};

const mutations = {};

const actions = {


    initialize({dispatch, commit, getters}) {
        return new Promise((resolve, reject) => {
            window.console.log('startup', 'Initializing from page data');

            dispatch('loadIsAdminFromPageData').then(function () {

                dispatch('loadMeetingFromPageData').then(function () {

                    dispatch('loadMotionFromPageData').then(function () {

                        let meeting = getters.getActiveMeeting;

                        dispatch('initializeMeetingListeners');

                        //get existing motions for meeting
                        dispatch('loadMotionsForMeeting', meeting.id).then(function () {

                            //get motions which have already been handled
                            dispatch('loadMotionsUserHasVotedUpon', meeting.id).then(function () {

                                dispatch('loadResultsForAllMeetingMotions').then(function () {
                                });

                                dispatch('loadMotionTypesAndTemplates').then(function () {
                                });

                            });

                        });

                    });

                });

            });
        });
    },

    initializeMeetingListeners({dispatch, commit, getters}) {
        let meeting = getters.getActiveMeeting;
        let channel = `meeting.${meeting.id}`;
        Echo.private(channel)
            .listen("MotionSeekingSecond", (e) => {
                window.console.log('Received broadcast event meeting', e);
                dispatch('handleMotionSeekingSecondMessage', e);
            })
            .listen("MotionSeconded", (e) => {
                window.console.log('Received broadcast event meeting', e);
                //Switches to the motion which has now been approved and seconded
                dispatch('handleMotionSecondedMessage', e);
            })
            .listen("NoSecondObtained", (e) => {
                window.console.log('Received broadcast event meeting', e);
                dispatch('handleNoSecondObtainedMessage', e);
            })
            .listen('MotionMarkedOutOfOrder', (e) => {
                window.console.log('Received broadcast event meeting', e);
                dispatch('handleMotionMarkedOutOfOrderMessage', e);
            })
            .listen('NewCurrentMotionSet', (e) => {
                //In some cases the chair may select a motion from the
                //home page. When that heppens we need to force everyone onto
                //a new motion
               dispatch('handleNewCurrentMotionSetMessage', e);
            });

        window.console.log('Meeting listeners initialized for ', channel);

        if (getters.getIsAdmin) {
            let chairChannel = `chair.${meeting.id}`;
            Echo.private(chairChannel)
                .listen('MotionNeedingApproval', (e) => {
                    window.console.log('Received chair broadcast', chairChannel,  e);

                    dispatch('handleMotionNeedingApprovalMessage', e);

                });

            window.console.log('Chair listeners initialized for ', chairChannel);
        }
    },

    /**
     * Sets whether the user is a meeting administrator
     * (and thus allowed to see certain pages) from page data.
     *
     * @param dispatch
     * @param commit
     * @param getters
     */
    loadIsAdminFromPageData({dispatch, commit, getters}) {
        return new Promise((resolve, reject) => {
            let data = window.startData;

            if (_.isUndefined(data.isAdmin) || _.isNull(data.isAdmin)) {
                console.log('Page data does not contain admin info ');
                return resolve();
            }

            console.log("Reading admin from page data", data.isAdmin);

            let p = Payload.factory({
                'updateProp': 'isAdmin',
                'updateVal': data.isAdmin
            });

            commit('setAdmin', p);

            resolve();

        });
    },

    /**
     * Parses the things set in window.startData
     * and sets them in store
     */
    loadMotionFromPageData({dispatch, commit, getters}) {
        return new Promise((resolve, reject) => {
            let data = window.startData;

            if (_.isUndefined(data.motion) || _.isNull(data.motion)) {
                console.log('Page data does not contain motion ');
                return resolve();
            }

            console.log("Reading motion from page data", data.motion);
            let motion = new Motion(data.motion);
            dispatch('setMotion', motion).then(() => {
                resolve();
            });

        });
    },


    /**
     * Parses the things set in window.startData
     * and sets them in store
     */
    loadMeetingFromPageData({dispatch, commit, getters}) {
        return new Promise((resolve, reject) => {
            let data = window.startData;
            window.console.log('startup', 'start data', 25, data);
            let meetingId = null;
            if (!_.isUndefined(data.meeting_id)) {
                meetingId = data.meeting_id;
            }
            //prefer the id if it is set directly on page
            else if (!_.isUndefined(data.motion.meeting_id)) {
                meetingId = data.motion.meeting_id;
            }

            if (_.isNull(meetingId)) {
                console.log('No meeting id in page data');
                return resolve();
            }


            console.log("Loading meeting from server from id in page data")
            dispatch('loadMeeting', meetingId).then(function () {
                return resolve();
            });

        });
    }

};

const getters = {};


export default {
    actions,
    getters,
    mutations,
    state,
}
