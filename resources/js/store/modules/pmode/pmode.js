import Chair from './pmode.chair';
import Amend from './pmode.amendments';

const state = {
    ...Amend.state,
    ...Chair.state,

    inPmode: false,


    //things: []
};

const mutations = {
    ...Amend.mutations,
    ...Chair.mutations,

    setPmode: (state) => {
        state.inPmode = true;
    }
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

    isInPmode: (state) => {
        return state.inPmode;
    },

    /**
     * Returns tbe initial motions for all resolutions
     * @param state
     * @param getters
     */
    getResolutionRoots : (state, getters) => {
let rs = getters.getResolutions;
return rs.filter((r) => {
return _.isNull(r.applies_to)
});
    }
};

export default {
    actions,
    getters,
    mutations,
    state,
}
