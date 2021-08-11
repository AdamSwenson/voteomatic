import Motion from "../../models/Motion";
import {isReadyToRock} from "../../utilities/readiness.utilities";

const state = {
    currentVotesCast: null,

    numberOfMembers: null
};

const mutations = {
    setCurrentVotesCast: (state, newCount) => {
        state.currentVotesCast = newCount;
    },

    setNumberOfMembers: (state, memberCount) => {
        state.numberOfMembers = memberCount;
    },

    resetCastVotesCount: (state) => {
        state.currentVotesCast = null;
    }

    /*
    *   addThing: (state, thing) => {
    *        state.things.push(thing);
    *    }
    */

};


const actions = {

    handleCastVoteMessage({dispatch, commit, getters}, pusherEvent) {
        return new Promise(((resolve, reject) => {
            let motion = new Motion(pusherEvent.motion);
            let currentMotion = getters.getActiveMotion;
            if (motion.id === currentMotion.id) {
                //Since we can't assume we're going to get the
                //pusher notices in the order votes are cast
                //we only update if the new value is bigger.
                let count = pusherEvent.count;
                let currentCount = getters.getCastVotesCount;
                if (count > currentCount) {
                    commit('setCurrentVotesCast', count);
                }
                let members = getters.getMemberCount;
                if (! isReadyToRock(members) || pusherEvent.totalMembers > members) {
                    //We probably will only hit this the first time we get a vote
                    //though it is possible someone joins and votes right away
                    commit('setNumberOfMembers', pusherEvent.totalMembers)
                }
            }
            resolve();
        }));
    },

    /**
     * Resets the stored number of votes cast.
     * Should be called whenever we switch motions
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    resetCounts({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            commit('resetCastVotesCount');
            //probably don't need to update the number of
            //people present
        }));
    },

    // loadMemberCount({dispatch, commit, getters}, meeting) {
    //     return new Promise(((resolve, reject) => {
    //     }));
    // },


    /*
    *    doThing({dispatch, commit, getters}, thingParam) {
    *        return new Promise(((resolve, reject) => {
    *        }));
    *    },
    */
};

/**
 *
 *    getThingViaId: (state) => (thingId) => {
 *        return state.things.filter(function (c) {
 *            return c.thing_id === thingId;
 *        })
 *    },
 *
 *
 *    getThing: (state, getters) => {}
 */
const getters = {

    getCastVotesCount: (state) => {
        return state.currentVotesCast;
    },

    getOutstandingVotesCount: (state, getters) => {
        let votes = getters.getCastVotesCount;
        let members = getters.getMemberCount;

        if (_.isNull(votes) || _.isNull(members)) return null;

        return members - votes;
    },

    getMemberCount: (state) => {
        return state.numberOfMembers;
    }
};

export default {
    actions,
    getters,
    mutations,
    state,
}
