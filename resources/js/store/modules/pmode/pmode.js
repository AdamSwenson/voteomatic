import Chair from './pmode.chair';
import Amend from './pmode.amendments';
import {isReadyToRock} from "../../../utilities/readiness.utilities";

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
     * Returns list of unique group ids in descending order
     * for motions
     * having is_resolution = true
     * @param state
     * @param getters
     * @returns {*[]}
     */
    getResolutionGroupIds: (state, getters) => {
        let rs = getters.getResolutions;

        let ids = [];
        _.forEach(rs, (r) => {
            if (isReadyToRock(r, 'groupId')) {
                ids.push(r.groupId);
            }

        });

        ids = _.uniq(ids).sort().reverse();
        return ids;

    },

    getNewestGroupMember: (state, getters) => (groupId) => {
        let rezs = getters.getResolutionGroupMembers(groupId);
        let m =  _.orderBy(rezs, 'id', 'desc');
    return m[0];
        },

    getResolutionGroupMembers: (state, getters) => (groupId) => {
        let rezs = getters.getResolutions;
        return rezs.filter((r) => {
            return r.groupId === groupId;
        });

    },

    getResolutionsForPModeDisplay: (state, getters) => {
        let ids = getters.getResolutionGroupIds;
        //dev interestingly this returns a list of accordians showing each step in the tree
        // return getters.getNewestGroupMember(ids[0]);

        let out = [];
        _.forEach(ids, (groupId) => {
            out.push(getters.getNewestGroupMember(groupId));
        });
        return out;

    },


    /**
     * Returns tbe initial motions for all resolutions
     * @param state
     * @param getters
     */
    getResolutionRoots: (state, getters) => {
        let ids = getters.getResolutionGroupIds;
        let rezs = getters.getResolutions;
        let roots = [];

        _.forEach(ids, (groupId) => {
            //The initial resolution will have its id
            //as the group id
            let m = _.find(rezs, (r) => {
                return groupId === r.id;
            });
            if (isReadyToRock(m)) roots.push(m);
            // let members = rezs.filter((r) => {
            //     return r.groupId === r.id;
            // });
        });
        return roots;

        //     let roots = {};
        //
        // _.forEach(ids, (groupId) => {
        //     //Get everyone for this groupId
        //     let members = rezs.filter((r) => {
        //         return r.groupId === groupId;
        //     });
        //     members.sort((a, b) => {
        //         return a.id > b.id;
        //     });
        //     roots[groupId] = members[0];
        // });

        // return roots;
    }
};

export default {
    actions,
    getters,
    mutations,
    state,
}
