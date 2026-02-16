import routes from "../../routes";
import Vote from "../../models/Vote";

const state = {
    //The actual vote objects will be stored on castVotes
    //This just holds instructions for what messages to display
    showReceiptValid : false,

    showReceiptInvalid : false,

};


const mutations = {
    /*
    *   addThing: (state, thing) => {
    *        state.things.push(thing);
    *    }
    */

    setReceiptValid (state) {
        state.showReceiptValid = true;
        state.showReceiptInvalid = false;
    },

    setReceiptInvalid (state) {
        state.showReceiptInvalid = true;
        state.showReceiptValid = false;
    },

    resetValidityAlerts (state) {
        state.showReceiptValid = false;
        state.showReceiptInvalid = false;
    }


};

/**
 * UNUSED SO FAR
 * @type {{requestElectionValidation({dispatch: *, commit: *, getters: *}, *): Promise<unknown>, requestValidation({dispatch: *, commit: *, getters: *}, *): Promise<unknown>}}
 */
const actions = {

    requestElectionValidation({dispatch, commit, getters}, receipt) {

            // return new Promise((resolve, reject) => {
            //     let url = routes.receipts.validateReceipt();
            //     let payload = {receipt: receipt};
            //
            //     return axios.post(url, payload,
            //         {
            //             validateStatus: function (status) {
            //                 if (status === 422) {
            //                     window.console.log('vote-verification-page', 'validateStatus', 218, status);
            //                     dispatch('showServerProvidedMessage', {'message': "Please enter your receipt in the box below"})
            //                     return false;
            //                 }
            //                 return true;
            //             }
            //         })
            //         .then(function (response) {
            //             if (!_.isUndefined(response.data.id)) {
            //
            //                 if (! me.isElection) {
            //                     me.vote = new Vote({
            //                         motionId: response.data.motion_id,
            //                         candidateId: response.data.candidate_id
            //                     });
            //
            //                 } else {
            //                     //The is_yay prop being undefined will report the
            //                     //receipt as invalid. The error will be caught below
            //                     me.vote = new Vote({isYay: response.data.is_yay});
            //                 }
            //
            //                 me.showGood = true;
            //
            //             } else {
            //                 me.showBad = true;
            //             }
            //         }).catch(function (error) {
            //             me.showBad = true;
            //
            //         });
            // });
        },


    requestValidation({dispatch, commit, getters}, receipt) {

    return new Promise((resolve, reject) => {
        let url = routes.receipts.validateReceipt();
        let payload = {receipt: receipt};

        return axios.post(url, payload,
            {
            validateStatus: function (status) {
                if (status === 422) {
                    window.console.log('vote-verification-page', 'validateStatus', 218, status);
                    dispatch('showServerProvidedMessage', {'message': "Please enter your receipt in the box below"})
                    return false;
                }
                return true;
            }
        })
            .then(function (response) {
                if (!_.isUndefined(response.data.id)) {

                    if (! me.isElection) {
                        me.vote = new Vote({
                            motionId: response.data.motion_id,
                            candidateId: response.data.candidate_id
                        });

                    } else {
                        //The is_yay prop being undefined will report the
                        //receipt as invalid. The error will be caught below
                        me.vote = new Vote({isYay: response.data.is_yay});
                    }

                    me.showGood = true;
                    commit('setReceiptValid');


                } else {
                    commit('setReceiptInvalid');


                }
            }).catch(function (error) {
            commit('setReceiptInvalid');

        });
    });
}
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

    getShowReceiptValid : (state) => {
        return state.showReceiptValid;
    },

    getShowReceiptInvalid: (state) => {
        return state.showReceiptInvalid;
    }

};

export default {
    actions,
    getters,
    mutations,
    state,
}
