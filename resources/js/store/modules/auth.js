import * as routes from "../../routes";
import {normalizedRouteRoot} from "../../utilities/url.utilities";

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
     * Logs the user out and sends them to
     * the index
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    logout({dispatch, commit, getters},) {
        return new Promise(((resolve, reject) => {
            let url = routes.auth.logout();
            return Vue.axios.post(url,)
                .then((response) => {
                    let url2 = normalizedRouteRoot();
                    window.open(url2, '_self');
                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));
    },

    openHomePage({dispatch, commit, getters},) {
        return new Promise(((resolve, reject) => {
            let url = routes.home();
            window.open(url, '_self');
            resolve();
        }));
    },

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
