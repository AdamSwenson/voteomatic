import motion from "../../models/Motion";
import * as routes from "../../routes";


/**
 * Created by adam on 2020-07-30.
 */


const state = {

    /**
     * The motion being voted on,
     * currently edited, or whose
     * results are being reported
     */
    motion: null
};

const mutations = {

    setMotion: (state, payload) => {
        Vue.set(state, 'motion', payload);
    }
};

const actions = {

    /**
     * Create a new motion on the server and set
     * it as the current motion
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    createMotion({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource();
            return Vue.axios.post(url)
                .then((response) => {
                    let d = response.data;

                    let motion = new Motion(d);
                    // let motion = new Motion(d.id, d.name, d.date);
                    commit('setMotion', motion);
                    resolve()
                });
        }));

    },

    /**
     * Gets the motion from the server
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @returns {Promise<unknown>}
     */
    loadMotion({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource(motion.id);
            return Vue.axios.get(url)
                .then((response) => {
                    let d = response.data;
                    let motion = new Motion(d);
                    // let motion = new Motion(d.id, d.name, d.date);
                    commit('setMotion', motion);
                    resolve()
                });
        }));
    },


    /**
     * Sends new field entries to server
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param motion
     * @returns {Promise<unknown>}
     */
    updateMotion({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource(motion.id);
            // Vue.axios.put(url,  this.motion).then((response) => {
            // Vue.axios.post(url, this.motion).then((response) => {
            return Vue.axios.post(url, {data: motion, _method: 'put'})
                .then((response) => {
                    let d = response.data;
                    resolve()
                });
        }));
    }
};

const getters = {

    getMotion: (state) => {
        return state.motion;
    }
};


export default {
    actions,
    getters,
    mutations,
    state,
}
