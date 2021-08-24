import * as routes from "../../routes";
import Settings from "../../models/Settings";

const state = {
    settings: null
    //things: []
};

const mutations = {
    setSettings: (state, settingsObj) => {
        state.settings = settingsObj;
    },

    setSettingsProp: (state, {updateProp, updateVal}) => {
        Vue.set(state.settings, updateProp, updateVal);
    }

    /*
    *   addThing: (state, thing) => {
    *        state.things.push(thing);
    *    }
    */

};


const actions = {
    loadSettings({dispatch, commit, getters}, meetingId) {
        let data = {
            meetingId
        };

        let url = routes.settings.load(meetingId);

        return new Promise(((resolve, reject) => {
            return Vue.axios.get(url)
                .then((response) => {
                    let settings = new Settings(response.data);
                    commit.setSettings(settings);
                    resolve();
                });
        }));
    },

    /**
     * Updates a settings value locally and on server
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param payload
     * @returns {Promise<unknown>}
     */
    updateSettings({dispatch, commit, getters}, payload) {
        //change the value locally
        commit(payload);
        let settingsObj = getters.getSettings;

        let url = routes.settings.resource(settingsObj.id);

        return new Promise(((resolve, reject) => {
            return Vue.axios.put(url, settingsObj)
                .then((response) => {
                    //todo consider rollback on error
                });
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
const getters = {
    getSettings: (state) => {
        return state.settings;
    }
};

export default {
    actions,
    getters,
    mutations,
    state,
}
