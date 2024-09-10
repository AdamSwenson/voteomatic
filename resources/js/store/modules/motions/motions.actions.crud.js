import * as routes from "../../../routes";
import Message from "../../../models/Message";
import Motion from "../../../models/Motion";
import BallotObjectFactory from "../../../models/BallotObjectFactory";
import Resolution from "../../../models/Resolution";

const actions = {
    /*
    *    doThing({dispatch, commit, getters}, thingParam) {
    *        return new Promise(((resolve, reject) => {
    *        }));
    *    },
    */
    /**
     * Create a new motion on the server and set
     * it as the current motion.
     *
     * This is used by the regular member. It does not set the current motion
     * since a member making a motion will need it to be approved and seconded.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    createMotion({dispatch, commit, getters}, meetingId) {
        let me = this;
        return new Promise(((resolve, reject) => {
            // window.console.log('creating');
            // let statusMessage = Message.makeFromTemplate('pendingApproval');
            // window.console.log(statusMessage);
            // commit('addToMessageQueue', statusMessage);

            //send to server
            let url = routes.motions.resource();
            let p = {'meetingId': meetingId};
            // window.console.log('sending', p);
            return Vue.axios.post(url, p)
                .then((response) => {
                    //Set a message for the user telling them what's going to happen
                    let statusMessage = Message.makeFromTemplate('pendingApproval');
                    //set it on a timer
                    dispatch('showMessage', statusMessage);
                    resolve();

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
     * Uses the current state of the draft motion to
     * create a new motion on the server
     *
     * The server will handle setting it as current, if the user is the chair or
     * soliciting the chair's approval if they are a regular member.
     *
     * NB, nothing needs to be passed in since everything relevant is stored in
     * state
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    createMotionFromDraft({dispatch, commit, getters}) {
        let meeting = getters.getActiveMeeting;
        let motion = getters.getDraftMotion;

        let me = this;
        return new Promise(((resolve, reject) => {

            //send to server
            let url = routes.motions.resource();
            // let p = {'meetingId': meetingId};
            motion['meetingId'] = meeting.id;
            // window.console.log('sending', p);
            return Vue.axios.post(url, motion)
                .then((response) => {
                    //Set a message for the user telling them what's going to happen
                    let statusMessage = Message.makeFromTemplate('pendingApproval');
                    //set it on a timer
                    dispatch('showMessage', statusMessage);
                    resolve(response);
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
     * Uses a motion template to create the motion.
     * @param dispatch
     * @param commit
     * @param getters
     * @param template
     * @returns {Promise<unknown>}
     */
    createMotionFromTemplate({dispatch, commit, getters}, template) {
        return new Promise(((resolve, reject) => {
            let meeting = getters.getActiveMeeting;

            //send to server
            let url = routes.motions.resource();
            let d = template;
            d['meetingId'] = meeting.id;
            // let p = {'meetingId': meetingId};
            // window.console.log('sending', d);

            return Vue.axios.post(url, d)
                .then((response) => {
                    let d = response.data;

                    let statusMessage = Message.makeFromTemplate('pendingApproval');
                    //let them know the chair will need to approve
                    dispatch('showMessage', statusMessage);

                    //dev Not going to use this since the spinner (VOT-85) works better and doesn't hang around after the motion has loaded
                    //The chair won't see the above message. The user won't see this one
                    // let statusMessage2 = Message.makeFromTemplate('settingUpMotion');
                    // dispatch('showMessage', statusMessage2)
                    resolve();
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
     * Create a new motion on the server and set
     * it as the current motion
     *
     * dev This probably won't end up being used. Keeping the set current motion logic for now
     * @deprecated
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    createMotionByChair({dispatch, commit, getters}, meetingId) {
        let me = this;
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource();
            let p = {'meetingId': meetingId};
            // window.console.log('sending', p);
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


    /**
     * Creates a motion which depends on another motion, e.g., an amendment
     * or tabling
     * @param dispatch
     * @param commit
     * @param getters
     * @param payload
     * @returns {Promise<unknown>}
     */
    createSubsidiaryMotion({dispatch, commit, getters}, payload) {
        let me = this;
        return new Promise(((resolve, reject) => {
            //send to server
            let url = routes.motions.resource();

            // window.console.log('sending', p);
            return Vue.axios.post(url, payload)
                .then((response) => {
                    //Set a message for the user telling them what's going to happen
                    let statusMessage = Message.makeFromTemplate('pendingApproval');
                    //set it on a timer
                    dispatch('showMessage', statusMessage);
                    //dev Not going to use this since the spinner (VOT-85) works better and doesn't hang around after the motion has loaded
                    //The chair won't see the above message. The user won't see this one
                    // let statusMessage2 = Message.makeFromTemplate('settingUpMotion');
                    // dispatch('showMessage', statusMessage2)

                    // window.console.log('rez1', payload, response);
                    let r = BallotObjectFactory.make(response.data)
                    // if (r.isResolutionAmendment) {
                    //     //create a resolution object. This will normally be handled
                    //     //by pusher, but we need the object's id to update the
                    //     //tagged text
                    //     // let r = BallotObjectFactory.make(response.data)
                    //     window.console.log('rez', r);
                    //
                    //     return dispatch('diffTagResolutionAmendment', r).then(() => {
                    //         return resolve();
                    //     });
                    //
                    // } else {

                    //We return the object created from the data without saving it
                    //to store. This is because the calling action may need to do more
                    //with the object's id without waiting for pusher.
                    return resolve(r);
                    // }

                })
                .catch(function (error) {
                    // error handling
                    if (error.response) {
                        dispatch('showServerProvidedMessage', error.response.data);
                    }
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
                        dispatch('setMotion', newActive);

                    }
                    return resolve()
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
     * Create a draft motion on the client. This is what the user
     * edits before they click 'make motion'. After that, editing would
     * be done on the main motion.
     *
     * This may help with the problem of the user getting pulled away while
     * working on their motion (VOT-75)
     *
     * @param dispatch
     * @param commit
     * @param getters
     */
    initializeDraftMainMotion({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {

            let motion = new Motion({
                type: 'main',
                requires: 0.5,
                debatable: true
            });
            commit('setDraftMotion', motion);
            resolve();
        }));

    },

    /**
     * Same as initializeDraftMainMotion but for resolutions
     * @param dispatch
     * @param commit
     * @param getters
     * @returns {Promise<unknown>}
     */
    initializeDraftResolution({dispatch, commit, getters}) {
        return new Promise(((resolve, reject) => {
            let motion = new Resolution({
                type: 'resolution',
                requires: 0.5,
                debatable: true,
                is_resolution: true,
                info: {
                    title: '',
                    resolutionIdentifier: '',
                    formattedContent: '',
                    groupId: null
                }
            });
            commit('setDraftMotion', motion);
            resolve();
        }));


    },

    /**
     * Updates properties of the draft motion the user
     * is working on. Does not tell the server anything.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param payload
     * @returns {Promise<unknown>}
     */
    updateDraftMotion({dispatch, commit, getters}, payload) {
        return new Promise(((resolve, reject) => {
            //make local change only
            commit('setDraftMotionProp', payload)
            resolve();
        }));
    }


};


export {actions as default }
