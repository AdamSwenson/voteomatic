import Motion from "../../models/Motion";
import * as routes from "../../routes";
import Payload from "../../models/Payload";

/**
 * Created by adam on 2020-07-30.
 */


/**
 * Returns the object stored in the array which
 * has the provided id
 *
 * @param storageArray
 * @param id
 * @returns {*}
 */
function getById(storageArray, id){
    // return function ( state, id ) {
    let r = storageArray.filter(function (i) {
        if (i.id === id) {
            return i;
        }
    });
    return r[0];
}

const state = {

    /**
     * The id of the motion being voted on,
     * currently edited, or whose
     * results are being reported
     */
    currentMotion: null,

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
        window.console.log(motionObject);
        //todo double check that there is no reason to have duplicates or raise an error
        let mi = -1;
        _.forEach(state.motions, function (m) {
            if (m.id === motionObject.id) {
                mi = 1;
            }
        });

        if (mi === -1) {
            state.motions.push(motionObject);
        }


        // state.motions.push(motionObject);
        // Vue.set(state, 'motion', payload);

    },

    deleteMotion: (state, motionObject) => {
        // let idx = state.motions.indexOf(motionObject);
        // state.motions.pop(idx);
        _.remove(state.motions, function (motion) {
            return motion.id === motionObject.id;
        });

    },

    /**
     * Sets the provided motion object as
     * the currently active motion
     *
     * @param state
     * @param motionObject
     */
    setMotion: (state, motionObject) => {
        Vue.set(state, 'currentMotion', motionObject.id);
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
        if (!state.motionIdsUserHasVotedUpon.includes(motionId)) {
            state.motionIdsUserHasVotedUpon.push(motionId);
        }
    },

    /**
     * Updates a property on the motion object
     * @param state
     * @param prop
     * @param val
     */
    setMotionProp: (state, {updateProp, updateVal}) => {
        // window.console.log(updateProp, updateVal);
        let currentMotion = getById(state.motions, state.currentMotion);

        Vue.set(currentMotion, updateProp, updateVal);

        // Vue.set(state.currentMotion, updateProp, updateVal);
    },


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
        let me = this;
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource();
            let p = {'meetingId': meetingId};
            window.console.log('sending', p);
            return Vue.axios.post(url, p)
                .then((response) => {
                    let d = response.data;

                    let motion = new Motion(d);
                    // let motion = new Motion(d.id, d.name, d.date);
                    commit('addMotionToStore', motion);

                    let pl = {meetingId: meetingId, motionId: motion.id};

                    return dispatch('setCurrentMotion', pl)
                        .then(() => {
                            return resolve(motion);
                        });

                    // commit('setMotion', motion);

                });
        }));

    },

    deleteMotion({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource(motion.id);
            return Vue.axios.delete(url)
                .then((response) => {
                    let d = response.data;

                    //remove it from the list of motions
                    commit('deleteMotion', motion);

                    //check whether it is the currently set motion
                    let activeMotino = getters.getActiveMotion;
                    if (activeMotino.id === motion.id) {
                        //we need to remove it and set another in its place
                        let newActive = getters.getStoredMeetings[0];
                        commit('setMotion', newActive);

                    }
                    return resolve()
                });
        }));

    },


    endVotingOnMotion({dispatch, commit, getters}, motion) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.endVoting(motion.id);
            return Vue.axios.post(url)
                .then((response) => {
                    let d = response.data;

                    //todo this means that the motion must be selected in order to end voting. That probably makes sense...
                    commit('setMotion', motion);

                    let pl = Payload.factory({object: motion, updateProp: 'isComplete', updateVal: d.is_complete});

                    //we leave it as the currently set motion so that
                    //the results tab will provide results for the
                    //immediate past motion.
                    //Instead, we just update the completed property on the
                    //motion
                    commit('setMotionProp', pl);


                    // let motion = new Motion(d);
                    // // let motion = new Motion(d.id, d.name, d.date);
                    // commit('addMotionToStore', motion);
                    // commit('setMotion', motion);
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
                    // let motion = new Motion(d);
                    commit('addVotedUponMotion', d.id);
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
                        if (d.is_current) {
                            commit('setMotion', motion)
                        }
                    });
                    resolve()

                });
        }));
    },

    /**
     * Sets the motion as the current one on the server
     * and updates the local store
     * @param dispatch
     * @param commit
     * @param getters
     * @param meetingId
     * @param motionId
     * @returns {Promise<unknown>}
     */
    setCurrentMotion({dispatch, commit, getters}, {meetingId, motionId}) {
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.setCurrentMotion(meetingId, motionId);
            return Vue.axios.post(url)
                .then((response) => {
                    let motion = getters.getMotionById(motionId);
                    commit('setMotion', motion)
                    resolve()
                });
        }));
    },

    /**
     * Sends new field entries to server and
     * adds them to the currently active motion
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
        return getById(state.motions, state.currentMotion)
        // return state.currentMotion;
    },

    getMotionById: (state, id) => (id) => {
        // return function ( state, id ) {
        let r = state.motions.filter(function (i) {
            if (i.id === id) {
                return i;
            }
        });
        return r[0];
    },
    // }( state, id )

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
        _.forEach(state.motionIdsUserHasVotedUpon, (motionId) => {
            let r = state.motions.filter(function (i) {
                if (i.id === motionId) {
                    return i;
                }
            });
            out.push(r[0]);
        })
        return out;
        //
        // let r = state.motions.filter(function (i) {
        //     if (i.id === id) {
        //         return i;
        //     }
        // });
        // return r[0];
        // return state.motionIdsUserHasVotedUpon;
    },

    getMotionIdsUserVotedUpon: (state) => {
        let out = [];
        _.forEach(state.motionIdsUserHasVotedUpon, (mid) => {
            out.push(mid);
        })
        return out;
    },

    /**
     * Whether the user has voted on the motion which is
     * currently active
     * @param state
     */
    hasVotedOnCurrentMotion: (state) => {
        return state.motionIdsUserHasVotedUpon.indexOf(state.currentMotion) > -1

        // return state.motionIdsUserHasVotedUpon.indexOf(state.currentMotion.id) > -1
    },


    /**
     * Not actually a getter from state; doing this way to keep
     * the definitions centrally. Returns the
     * dict with all the properties of the standard motion
     * templates.
     * @param state
     */
    getStandardMotionDefinitions: (state) => {
        return [
            {
                name: 'Adjourn',
                content: "That the meeting be adjourned.",
                description: "Meeting comes to an end.",
                requires: 0.5
            },


            {
                name: 'Committee of the Whole',
                content: "That the body convene as a committee of the whole with this body's Chair as its Chair ",
                description: "The formal deliberative process is suspended. The body" +
                    " may work informally on an issue. No votes taken while in the committee of the whole " +
                    "are binding on the main body but they may be used to advise the main body on what to do. " +
                    "To communicate from the committee of the whole, the committee " +
                    "of the whole should vote to Rise and Report",
                requires: 0.5
            },

            {
                name: 'Previous Question (Call the Question)',
                content: "That the pending question is called",
                description: "If approved, all debate ends on the pending motion and " +
                    "the body moves immediately to a vote on the pending motion. If fails," +
                    "debate continues on the pending motion",
                requires: 0.66
            },
            {
                name: 'Place on the Table',
                content: "The pending motion is placed on the table",
                description: "All action on the motion is paused so the body can attend to " +
                    "other business. There is no scheduled time to resume action. Action " +
                    "will resume upon a majority vote to Take from the Table. That motion may" +
                    "be made whenever no main motion is pending",
                requires: 0.5
            },


            {
                name: 'Recess',
                content: "That the body recess.",
                description: "We take a break. This can be qualified to " +
                    "say how long. The how long part is amendable.",
                requires: 0.5
            },

            {
                name: 'Reconsider (with notice)',
                content: "That the body reconsider the motion that ",
                description: "",
                requires: 0.5
            },

            {
                name: 'Reconsider (without notice)',
                content: "That the body reconsider the motion that ",
                description: "",
                requires: 0.66
            },


        ]


    }
};


export default {
    actions,
    getters,
    mutations,
    state,
}
