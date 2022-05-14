import * as routes from "../../../routes";
import MotionObjectFactory from "../../../models/MotionObjectFactory";
import {idify} from "../../../utilities/object.utilities";
import BallotObjectFactory from "../../../models/BallotObjectFactory";


const actions = {
    /*
    *    doThing({dispatch, commit, getters}, thingParam) {
    *        return new Promise(((resolve, reject) => {
    *        }));
    *    },
    */

    /**
     * Gets the motion from the server
     *
     * //dev Does this actually work? Shouldn't it add to store too?
     *
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
                    let motion = MotionObjectFactory.make(d);
                    // let motion = new Motion(d);
                    // let motion = new Motion(d.id, d.name, d.date);
                    dispatch('setMotion', motion);
                    resolve()
                })
                .catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
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
        window.console.log("Loading voted upon motions");
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.castVotes.getVotedMotions(meetingId);
            return Vue.axios.get(url)
                .then((response) => {
                    _.forEach(response.data, (d) => {

                        // todo or should we be storing ids? need to decide how best to do comparisons

                        // todo should we clear the store first? Can the list contain motions with duplicate ids?
                        // todo
                        // let motion = new Motion(d);
                        commit('addVotedUponMotion', d.id);
                    });
                    return resolve();
                }).catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));
    },

    loadMotionsForMeeting({dispatch, commit, getters}, meeting) {
        //we need this to determine whether election or regular
        // let meeting = getters.getMeetingById(meetingId);

        window.console.log('Loading motions for meeting ', meeting);
        return new Promise(((resolve, reject) => {

            //send to server
            let url = routes.motions.getAllMotionsForMeeting(idify(meeting));
            return Vue.axios.get(url)
                .then((response) => {
                    //Need to do this so that we don't have to
                    //check motions for their meetings every time
                    //we access the stack.
                    commit('clearMotions');

                    _.forEach(response.data, (d) => {
                        let m = BallotObjectFactory.make(d, meeting);

                        commit('addMotionToStore', m);
                        if (d.is_current) {
                            dispatch('setMotion', m)
                        }
                    });

                    resolve()

                })
                .catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
                });
        }));
    },

    loadMotionTypesAndTemplates({dispatch, commit, getters}) {
        window.console.log("Loading motion types and templates");
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.templates();
            return Vue.axios.get(url)
                .then((response) => {
                    commit('setMotionTemplates', response.data);

                    let url2 = routes.motions.types();
                    return Vue.axios.get(url2)
                        .then((response) => {
                            //todo Figure out easiest way to use loaded types. If not easy, then ignore. The point is to make it easier to keep definitions on client and server in sync.

                            // Motion.prototype.amendmentNames = response.data.amendment;
                            // Motion.prototype.proceduralMotionNames = response.data.amendment;
                            return resolve();
                        });
                });
        }));
    },

};

export {actions as default}
