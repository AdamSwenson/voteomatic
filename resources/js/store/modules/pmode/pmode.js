import Chair from './pmode.chair';

const state = {
    ...Chair.state,
    //things: []
};

const mutations = {
    ...Chair.mutations,
    /*
    *   addThing: (state, thing) => {
    *        state.things.push(thing);
    *    }
    */

};


const actions = {
    ...Chair.actions,
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
    ...Chair.getters,
};

export default {
    actions,
    getters,
    mutations,
    state,
}
