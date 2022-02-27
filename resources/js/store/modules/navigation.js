const state = {
    /** When set to true, a watcher will open the home tab */
    homeNavTrigger: false,

    /** When set to true, a watcher will open the results tab*/
    resultsNavTrigger: false,

    /** When set to true, a watcher will open the vote tab*/
    voteNavTrigger: false,
};


const mutations = {
    setHomeNavTrigger: (state, value) => {
    Vue.set(state, 'homeNavTrigger', value);
        },

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
        state.homeNavTrigger = false;
    }

};

const actions = {
    forceNavigationToHome({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            commit('resetNavTriggers');
            commit('setHomeNavTrigger', true);
            resolve();
        }));
    },

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

    /***
     * Opens a new page with the given url.
     * Usually used when creating a new meeting to ensure everything gets
     * cleaned out
     * @param dispatch
     * @param commit
     * @param getters
     * @param url
     */
    forceNavigationToUrl({dispatch, commit, getters}, url) {
        window.open(url, );
    },

};

const getters = {
    getHomeNavTrigger: (state) => {
        return state.homeNavTrigger;
    },

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
