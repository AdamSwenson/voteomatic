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


};

const actions = {
    // forceNavigationToResults({dispatch, commit, getters}) {
    //     return new Promise(((resolve, reject) => {
    //
    //     }));
    // },
    // forceNavigationVote({dispatch, commit, getters}) {
    //     return new Promise(((resolve, reject) => {
    //
    //     }));
    // },

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
