import Meeting from "../../models/Meeting";
import * as routes from "../../routes";


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

    loadMeeting({dispatch, commit, getters}, meeting) {
        let meetingId = _.isNumber(meeting) ? meeting : meeting.id;

        return new Promise(((resolve, reject) => {

            //send to server
            let url = routes.meetings.resource(meetingId);
            return Vue.axios.get(url)
                .then((response) => {
                    let d = response.data;
                    let meeting = new Meeting(d.id, d.name, d.date);
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
