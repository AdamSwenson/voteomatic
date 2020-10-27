/**
 * Initialization and other actions etc which
 * get called on startup
 *
 * Created by adam on 2020-07-30.
 */

import Motion from "../../models/Motion";

const state = {};

const mutations = {};

const actions = {


    initialize({dispatch, commit, getters}) {
        return new Promise((resolve, reject) => {
            window.console.log('startup', 'Initializing from page data');

            dispatch('loadMeetingFromPageData').then(function () {

                dispatch('loadMotionFromPageData').then(function () {

                    let meeting = getters.getMeeting;

                    //get existing motions for meeting
                    dispatch('loadMotionsForMeeting', meeting.id).then(function () {

                        resolve();

                    });


                })

            });

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
            commit('setMotion', motion);

            resolve();

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
