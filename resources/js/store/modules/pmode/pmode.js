import Chair from './pmode.chair';
import Amend from './pmode.amendments';

const state = {
    ...Amend.state,
    ...Chair.state,

    //things: []
};

const mutations = {
    ...Amend.mutations,
    ...Chair.mutations,
    /*
    *   addThing: (state, thing) => {
    *        state.things.push(thing);
    *    }
    */

};


const actions = {
    ...Amend.actions,
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
    ...Amend.getters,
    ...Chair.getters,
};

export default {
    actions,
    getters,
    mutations,
    state,
}
