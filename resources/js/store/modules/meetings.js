import Meeting from "../../models/Meeting";
import * as routes from "../../routes";


/**
 * Created by adam on 2020-07-30.
 */


const state = {

    meeting: null
};

const mutations = {

    setMeeting: (state, payload) => {
        Vue.set(state, 'meeting', payload);
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
                    commit('setMeeting', meeting);
                    resolve()
                });
        }));
    },


    updateMeeting({dispatch, commit, getters}, meeting) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.meetings.resource(meeting.id);
            // Vue.axios.put(url,  this.meeting).then((response) => {
            // Vue.axios.post(url, this.meeting).then((response) => {
            return Vue.axios.post(url, {data: meeting, _method: 'put'})
                .then((response) => {
                    let d = response.data;
                    resolve()
                });
        }));
    }
};

const getters = {

    getMeeting: (state) => {
        return state.meeting;
    }
};


export default {
    actions,
    getters,
    mutations,
    state,
}
