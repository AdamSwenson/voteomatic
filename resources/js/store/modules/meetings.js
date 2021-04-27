import Meeting from "../../models/Meeting";
import * as routes from "../../routes";
import Election from "../../models/Election";
import MainObjectFactory from "../../models/MainObjectFactory";


/**
 * Created by adam on 2020-07-30.
 */


const state = {

    meeting: null,

    meetings: []
};

const mutations = {

    /**
     * Pushes a meeting object into meetings
     * @param state
     * @param meetingObject
     */
    addMeetingToStore: (state, meetingObject) => {
        //todo double check that there is no reason to have duplicates or raise an error
        let mi = -1;
        _.forEach(state.meetings, function(m) {
            if(m.id === meetingObject.id){
                mi = 1;
            }
        });

        if(mi === -1) {
            state.meetings.push(meetingObject);
        }
    },


    deleteMeeting: (state, meetingObject) => {
        _.remove(state.meetings, function (meeting) {
            return meeting.id === meetingObject.id;
        });

    },

    setMeeting: (state, payload) => {
        Vue.set(state, 'meeting', payload);
    },

    /**
     * Updates a property on the meeting object
     * @param state
     * @param prop
     * @param val
     */
    setMeetingProp: (state, {updateProp, updateVal}) => {
        Vue.set(state.meeting, updateProp, updateVal);
    }
};

const actions = {

    createMeeting({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.meetings.resource();
            return Vue.axios.post(url)
                .then((response) => {
                    let d = response.data;
                    let meeting = new Meeting(d.id, d.name, d.date);
                    commit('addMeetingToStore', meeting);

                    commit('setMeeting', meeting);
                    resolve()
                });
        }));
    },


    deleteMeeting({dispatch, commit, getters}, meeting) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.meetings.resource(meeting.id);
            return Vue.axios.delete(url)
                .then((response) => {
                    let d = response.data;

                    //remove it from the list of meetings
                    commit('deleteMeeting', meeting);

                    //check whether it is the currently set meeting
                    let activeMeeting = getters.getActiveMeeting;
                    if (activeMeeting.id === meeting.id) {
                        //we need to remove it and set another in its place
                        let newActive = getters.getStoredMeetings[0];
                        commit('setMeeting', newActive);
                    }
                    return resolve()
                });
        }));

    },


    loadMeeting({dispatch, commit, getters}, meeting) {
        let meetingId = _.isNumber(meeting) ? meeting : meeting.id;

        return new Promise(((resolve, reject) => {

            //send to server
            let url = routes.meetings.resource(meetingId);
            return Vue.axios.get(url)
                .then((response) => {
                    //Will create either a Meeting or an Election object
                    let meeting = MainObjectFactory.make(response);
                    commit('addMeetingToStore', meeting);
                    commit('setMeeting', meeting);
                    resolve()
                });
        }));
    },

    /**
     * Loads all meetings which the user has
     * access to.
     * @param dispatch
     * @param commit
     * @param getters
     * @param meeting
     * @returns {Promise<unknown>}
     */
    loadAllMeetings({dispatch, commit, getters}) {
        // let meetingId = _.isNumber(meeting) ? meeting : meeting.id;

        return new Promise(((resolve, reject) => {

            //send to server
            let url = routes.meetings.resource();
            return Vue.axios.get(url)
                .then((response) => {
                    _.forEach(response.data, (d) => {
                        // window.console.log('loadAllMeetings', d);
                        let meeting = new Meeting(d.id, d.name, d.date);
                        commit('addMeetingToStore', meeting);
                        resolve()
                    });


                });
        }));
    },


    /**
     * Updates the meeting on the server and in
     * the vue store.
     * Expects a Payload object
     * @param dispatch
     * @param commit
     * @param getters
     * @param payload Payload
     * @returns {Promise<unknown>}
     */
    updateMeeting({dispatch, commit, getters}, payload) {
        return new Promise(((resolve, reject) => {
            //make local change first
            //todo consider whether worth rolling back
            commit('setMeetingProp', payload)

            let meeting = getters.getActiveMeeting;

            //send to server
            let url = routes.meetings.resource(meeting.id);
            return Vue.axios.post(url, {data: meeting, _method: 'put'})
                .then((response) => {
                    let d = response.data;
                    resolve()
                });
        }));
    }
};

const getters = {

    getActiveMeeting: (state) => {
        return state.meeting;
    },

    getStoredMeetings : (state) => {
    return state.meetings;
    }
};


export default {
    actions,
    getters,
    mutations,
    state,
}
