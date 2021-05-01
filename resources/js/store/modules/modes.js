import Election from "../../models/Election";
import Meeting from "../../models/Meeting";
import {isReadyToRock} from "../../utilities/readiness.utilities";

const state = {

    // /**
    //  * What mode we are in.
    //  *
    //  * It is very annoying to not just do this by the object
    //  * type that's set as current. But we don't want to create a ton
    //  * of empty meetings. Plus there's no way to toggle
    //  */
    // mode: 'meeting',

    isSetup: false,

    /**
     * What set of fields to show. Used to determine
     * if we are in editing mode.
     *
     * Values:
     *      null
     *      false
     *      edit
     *      create
     */
    showArea: null

};

const mutations = {
    /*
    *   addThing: (state, thing) => {
    *        state.things.push(thing);
    *    }
    */

    // setMode: (state, payload) => {
    //     if (payload instanceof String) {
    //         state.mode = mode;
    //     }
    //
    //     if (payload instanceof Election) {
    //         state.mode = payload.type;
    //     }
    //
    //     if (payload instanceof Meeting) {
    //         state.mode = payload.type;
    //     }
    //
    // },

    setIsSetup: (state, value) => {
        state.isSetup = value;
    },

    setShowArea: (state, value) => {
        state.showArea = value;
    }

};


const actions = {

    /**
     * Creates a new election and sets the mode
     * to election. (NB, this should reuse any blank election in the db
     * and thus not proliferate blanks);
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    setElectionMode({dispatch, commit, getters},) {
        return new Promise(((resolve, reject) => {
            dispatch('createElection').then(() => {
                let election = getters.getActiveMeeting;
                window.console.log('m', election);
                //This will clear the currently set motion,
                //which was probably a real motion.
                dispatch('createOffice', election).then(() => {
                    //This ensures that we see the editing fields when
                    //we go to the edit card
                    commit('setShowArea', 'edit');
                    resolve();
                });
            });
        }));
    },


    /**
     * Creates a new meeting and sets the mode
     * to meeting. (NB, this should reuse any blank meeting in the db
     * and thus not proliferate blanks);
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    setMeetingMode({dispatch, commit, getters},) {
        return new Promise(((resolve, reject) => {
            dispatch('createMeeting').then(() => {
                //This will clear the currently set motion,
                //which was probably an election office.
                dispatch('createOffice', getters.getActiveMeeting).then(() => {
                    commit('setShowArea', 'edit');
                    resolve();
                });
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


    isElection: (state, getters) => {
        let event = getters.getActiveMeeting;
        if(isReadyToRock(event)) {
            return event.type === 'election';
        }
    },

    isMeeting: (state, getters) => {
        let event = getters.getActiveMeeting;
        if(isReadyToRock(event)) {
            return event.type === 'meeting';
        }
    },


    /**
     * Returns the type property. this is the same as
     * get mode;
     * @param state
     * @param getters
     * @returns {*}
     */
    getEventType: (state, getters) => {
        let event = getters.getActiveMeeting;
        if(isReadyToRock(event)) {
            return event.type;
        }
    },

    /**
     * Returns the type property. this is the same as
     * get mode;
     * @param state
     * @param getters
     * @returns {*}
     */
    getEventSubsidiaryType: (state, getters) => {
        let event = getters.getActiveMeeting;
        if(isReadyToRock(event)) {
            return event.subsidiaryType;
        }
    },




    getMode: (state, getters) => {
        let event = getters.getActiveMeeting;
        if(isReadyToRock(event)) {
            return event.type;
        }
    },

    isSetup: (state, getters) => {
        return state.isSetup
    },

    showArea: (state, getters) => {
        return state.showArea;
    }

};

export default {
    actions,
    getters,
    mutations,
    state,
}
