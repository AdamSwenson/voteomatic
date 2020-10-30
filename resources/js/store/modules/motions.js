import Motion from "../../models/Motion";
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
    motion: null,

    /**
     * Store of loaded motions
     */
    motions: [],

    /**
     * Motions from this meeting which the user has already
     * cast a vote on and should be locked out of voting again
     */
    motionIdsUserHasVotedUpon: []
};

const mutations = {
    addMotionToStore: (state, motionObject) => {
        state.motions.push(motionObject);
        // Vue.set(state, 'motion', payload);

    },

    setMotion: (state, motionObject) => {
        Vue.set(state, 'motion', motionObject);
    },

    /**
     * Pushes a motion id into the list of motion is
     * which the user has already voted on. This is
     * used for client side restrictions on display.
     *
     * If the motion object is needed, it should be loaded
     * separately via the id.
     *
     * @param state
     * @param motionId
     */
    addVotedUponMotion: (state, motionId) => {
        //todo double check that there is no reason to have duplicates or raise an error
        if(! state.motionIdsUserHasVotedUpon.includes(motionId)){
            state.motionIdsUserHasVotedUpon.push(motionId);
        }
    },

    /**
     * Updates a property on the motion object
     * @param state
     * @param prop
     * @param val
     */
    setMotionProp : (state, {updateProp, updateVal}) => {
        Vue.set(state.motion, updateProp, updateVal);
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
    createMotion({dispatch, commit, getters}, meetingId) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource();
            let p = {'meetingId' : meetingId};
            return Vue.axios.post(url, p)
                .then((response) => {
                    let d = response.data;

                    let motion = new Motion(d);
                    // let motion = new Motion(d.id, d.name, d.date);
                    commit('addMotionToStore', motion);
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
     * Populates the store of ids which represent
     * motions the user has already cast a ballot on.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param meetingId
     * @returns {Promise<unknown>}
     */
    loadMotionsUserHasVotedUpon({dispatch, commit, getters}, meetingId) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.castVotes.getVotedMotions(meetingId);
            return Vue.axios.get(url).then((response) => {
                _.forEach(response.data, (d) => {

                    // todo or should we be storing ids? need to decide how best to do comparisons

                    // todo should we clear the store first? Can the list contain motions with duplicate ids?
                    // todo
                    let motion = new Motion(d);
                    commit('addVotedUponMotion', d);
                });
            });
        }));
    },

    loadMotionsForMeeting({dispatch, commit, getters}, meetingId) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.getAllMotionsForMeeting(meetingId);
            return Vue.axios.get(url)
                .then((response) => {
                    _.forEach(response.data, (d) => {
                        let motion = new Motion(d);
                        // let motion = new Motion(d.id, d.name, d.date);
                        commit('addMotionToStore', motion);
                    });
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
    updateMotion({dispatch, commit, getters}, payload) {
        return new Promise(((resolve, reject) => {
            //make local change first
            //todo consider whether worth rolling back
            commit('setMotionProp', payload)

            let motion = getters.getActiveMotion;

            //send to server
            let url = routes.motions.resource(motion.id);
            return Vue.axios.post(url, {data: motion, _method: 'put'})
                .then((response) => {
                    let d = response.data;
                    resolve()
                });
        }));
    }
};

const getters = {

    /**
     * Returns the currently set motion object.
     * This will be the motion that is currently being voted on,
     * edited, or whose results are being displayed.
     * @param state
     * @returns {null|{set: module.exports.computed.motion.set, get: (function(): module.exports.computed.motion.$store.getters.getMotion)}|{set: function(*=): void, get: function(): *}|(function(): *)|(function(): Motion)|Motion}
     */
    getActiveMotion: (state) => {
        return state.motion;
    },

    /**
     * Returns all motion objects which have
     * been loaded from the server. May include motions
     * the user has voted upon and un-voted motions.
     * @param state
     * @returns {[]|(function(): ([]|__webpack_exports__.default.computed.$store.getters.getStoredMotions))|{resource: (function(): string), getAllMotionsForMeeting: (function(*): string)}|{resource: (function(*=): string), getAllMotionsForMeeting: (function(*): string)}|{mutations: {addMotionToStore: function(*, *=): void, setMotion: function(*=, *=): void, addVotedUponMotion: function(*, *=): void}, state: {motion: null, motions: [], motionIdsUserHasVotedUpon: []}, getters: {getMotionsUserVotedUpon: function(*): *, getMotionIdsUserVotedUpon: function(*): [], getMotion: function(*): (null|{set: module.exports.computed.motion.set, get: (function(): module.exports.computed.motion.$store.getters.getMotion)}|{set: (function(*=): void), get: (function(): *)}|(function(): *)|(function(): Motion)|Motion), getStoredMotions: function(*): *}, actions: {createMotion({dispatch: *, commit?: *, getters: *}): Promise<unknown>, loadMotionsForMeeting({dispatch: *, commit?: *, getters: *}, *=): Promise<unknown>, loadMotionsUserHasVotedUpon({dispatch: *, commit?: *, getters: *}, *=): Promise<unknown>, loadMotion({dispatch: *, commit?: *, getters: *}, *): Promise<unknown>, updateMotion({dispatch: *, commit: *, getters: *}, *=): Promise<unknown>}}|(function(): ([]|default.computed.$store.getters.getStoredMotions))}
     */
    getStoredMotions: (state) => {
        return state.motions;
    },

    getMotionsUserVotedUpon: (state) => {
        let out = [];
        _.forEach(state.motionIdsUserHasVotedUpon, (motion) => {
            out.push(motion.id);
        })
        return out;
        return state.motionIdsUserHasVotedUpon;
    },

    getMotionIdsUserVotedUpon: (state) => {
        let out = [];
        _.forEach(state.motionIdsUserHasVotedUpon, (motion) => {
            out.push(motion.id);
        })
        return out;
    }
};


export default {
    actions,
    getters,
    mutations,
    state,
}
