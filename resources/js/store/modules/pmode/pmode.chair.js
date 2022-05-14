import Vote from "../../../models/Vote";

const state = {
    //things: []
};

const mutations = {
    /*
    *   addThing: (state, thing) => {
    *        state.things.push(thing);
    *    }
    */

};


const actions = {

    /**
     * When in parliamentarian mode and not actually conducting votes
     * this fabricates one yes vote records it, then closes voting
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @returns {Promise<unknown>}
     */
    markMotionAsPassed({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            let vote = new Vote(
                {
                    motionId: motion.id,
                    isYay: true
                });
            dispatch('startVotingOnMotion', motion).then(() => {
                dispatch('castMotionVote', vote).then(() => {
                    dispatch('endVotingOnMotion', motion).then(() => {
                        return resolve();
                    });
                });
            });

        }));
    },


    /**
     * When in parliamentarian mode and not actually conducting votes
     * this fabricates one no vote records it, then closes voting
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @returns {Promise<unknown>}
     */
    markMotionAsFailed({dispatch, commit, getters}, motion) {

        return new Promise(((resolve, reject) => {
            let vote = new Vote(
                {
                    motionId: motion.id,
                    isYay: false
                });

            dispatch('startVotingOnMotion', motion).then(() => {
                dispatch('castMotionVote', vote).then(() => {
                    dispatch('endVotingOnMotion', motion).then(() => {
                        return resolve();
                    });
                });
            });
        }));

    },

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
const getters = {};

export default {
    actions,
    getters,
    mutations,
    state,
}
