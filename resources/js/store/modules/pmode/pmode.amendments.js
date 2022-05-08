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

    getAmendments: (state, getters) => (motion) => {
        let primaryAmends = getters.getMotions.filter(function (i) {
            if (i.applies_to === motion.id) {
                return i;
            }
        });

        // let secondaryAmends = getters.getMotions.filter(function (i) {
        //     if (i.applies_to === motion.id) {
        //         return i;
        //     }


    }
};

export default {
    actions,
    getters,
    mutations,
    state,
}
