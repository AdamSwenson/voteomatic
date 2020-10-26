import motion from "../../models/Motion";
import * as routes from "../../routes";


/**
 * Created by adam on 2020-07-30.
 */


const state = {

    yayCount: null,
    nayCount: null,
    //this is separate since
    //for some uses will not send vote
    //totals to the client
    totalVotes: null,
    passed : null
};

const mutations = {

    setNayCount: (state, payload) => {
        Vue.set(state, 'nayCount', payload);
    },


    setYayCount: (state, payload) => {
        Vue.set(state, 'yayCount', payload);
    },
    setPassed: (state, payload) => {
        Vue.set(state, 'passed', payload);
    },
    setTotalVotes: (state, payload) => {
        Vue.set(state, 'totalVotes', payload);
    },
};

const actions = {

    /**
     * Gets the motion from the server
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @returns {Promise<unknown>}
     */
    loadCounts({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {

            let url = routes.results.getCounts(motion.id);

            return Vue.axios.get(url)
                .then((response) => {
                    let results = response.data;
                    commit('setYayCount', results.yayCount);
                    commit('setNayCount', results.nayCount);
                    resolve()
                });
        }));
    },

    loadResults({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {

            let url = routes.results.getResults(motion.id);

            return Vue.axios.get(url)
                .then((response) => {
                    let results = response.data;
                    commit('setPassed', results.passed);
                    commit('setTotalVotes', results.totalVotes);
                    resolve()
                });
        }));
    },


};

const getters = {

    getNayCount: (state) => {
        return state.nayCount;
    },

    getYayCount: (state) => {
        return state.yayCount;
    },

    getPassed: (state) =>{
      return state.passed;
    },
    getTotalVoteCount: (state)=>{
        return state.totalVotes;
        // return state.yayCount + state.nayCount;
    }
};


export default {
    actions,
    getters,
    mutations,
    state,
}
