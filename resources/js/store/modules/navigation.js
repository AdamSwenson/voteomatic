const state = {

    /** When set to true, a watcher will open the results tab*/
    resultsNavTrigger: false,

    /** When set to true, a watcher will open the vote tab*/
    voteNavTrigger: false
};


const mutations = {
    setResultsNavTrigger: (state, value) => {
        Vue.set(state, 'resultsNavTrigger', value);
    },

    setVoteNavTrigger: (state, value) => {
        Vue.set(state, 'voteNavTrigger', value);
    },

    /**
     * Sets all nav triggers to false.
     * This is used to avoid conflicts in where the user is forced to
     * @param state
     */
    resetNavTriggers: (state) => {
        state.resultsNavTrigger = false;
        state.voteNavTrigger = false;
    }

};

const actions = {
    forceNavigationToResults({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            commit('resetNavTriggers');
            commit('setResultsNavTrigger', true);
            resolve();
        }));
    },
    forceNavigationToVote({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            commit('resetNavTriggers');
            commit('setVoteNavTrigger', true);
            resolve();
        }));
    },

};

const getters = {

    getResultsNavTrigger: (state) => {
        return state.resultsNavTrigger;
    },

    getVoteNavTrigger: (state) => {
        return state.voteNavTrigger;
    },

};


export default {
    actions,
    getters,
    mutations,
    state,
}
