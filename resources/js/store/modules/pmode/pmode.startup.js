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

    initializePublicPmode({dispatch, commit, getters}) {
        return new Promise((resolve, reject) => {
            window.console.log('startup', 'Initializing meeting from page data');

            commit('setPublicPmode');

            dispatch('loadIsAdminFromPageData').then(function () {

                dispatch('loadMeetingFromPageData').then(function () {

                    // dispatch('loadMotionFromPageData').then(function () {

                        let meeting = getters.getActiveMeeting;

                        dispatch('initializePublicPmodeMeetingListeners');

                        //get existing motions for meeting
                        dispatch('loadMotionsForMeeting', meeting.id).then(function () {

                            //Set which motion accordion is open in pmode
                            dispatch('setOpenMotionToCurrent');

                            //get motions which have already been handled
                            // dispatch('loadMotionsUserHasVotedUpon', meeting.id).then(function () {

                                dispatch('loadResultsForAllMeetingMotions').then(function () {
                                });
                                //
                                // dispatch('loadMotionTypesAndTemplates').then(function () {
                                // });

                            // });

                        });

                    // });

                });

            });
        });
    },

    initializePublicPmodeMeetingListeners({dispatch, commit, getters}) {
        let meeting = getters.getActiveMeeting;
        let channel = `meeting.${meeting.id}`;
        Echo.private(channel)
            .listen("GeneralNotification", (e) => {
                dispatch('handlePusherGeneralNotification', e);
            })
            // .listen("MotionSeekingSecond", (e) => {
            //     window.console.log('Received broadcast event meeting', e);
            //     dispatch('handleMotionSeekingSecondMessage', e);
            // })
            .listen("MotionSeconded", (e) => {
                window.console.log('Received broadcast event meeting', e);
                //Switches to the motion which has now been approved and seconded
                dispatch('handleMotionSecondedMessage', e);
            })
            // .listen("NoSecondObtained", (e) => {
            //     window.console.log('Received broadcast event meeting', e);
            //     dispatch('handleNoSecondObtainedMessage', e);
            // })
            // .listen('MotionMarkedOutOfOrder', (e) => {
            //     window.console.log('Received broadcast event meeting', e);
            //     dispatch('handleMotionMarkedOutOfOrderMessage', e);
            // })
            .listen('NewCurrentMotionSet', (e) => {
                //In some cases the chair may select a motion from the
                //home page. When that heppens we need to force everyone onto
                //a new motion
                dispatch('handleNewCurrentMotionSetMessage', e);
            })

            .listen("ForcePageReload", () => {
            dispatch('handleForcePageReload');
            })
            // .listen('VotingOnMotionOpened', (e) => {
            //     dispatch('handleVotingOnMotionOpenedMessage', e);
            // });

        window.console.log('Public pmode meeting listeners initialized for ', channel);


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
