import Message from "../../models/Message";

const state = {
    //
    // messageText: '',
    //
    // messageStyle: '',
    //
    // displayTime: 0,
    //
    // showMessage: false,
    messageQueue: []
    //things: []
};

const mutations = {
    // setMessageText: (state, text) => {
    //     state.messageText = text;
    // },
    //
    // resetMessage: (state) => {
    //     state.messageText = '';
    //     state.messageStyle = '';
    //     state.displayTime = 0;
    //     state.showMessage = false;
    // },
    //
    // setMessageStyle: (state, style) => {
    //     state.messageStyle = style;
    // },

    addToMessageQueue: (state, messageObject) => {
        state.messageQueue.push(messageObject);
    },

    removeFromMessageQueue: (state, messageObject) => {
        window.console.log('removing', messageObject);
        state.messageQueue.splice(state.messageQueue.indexOf(messageObject),)
        _.remove(state.messageQueue, function (obj) {
            return obj.id === messageObject.id;
        });

    },

    clearMessageQueue: (state) => {
        state.messageQueue = [];
    }


    /*
    *   addThing: (state, thing) => {
    *        state.things.push(thing);
    *    }
    */

};


const actions = {
    /**
     * Shows the message for the amount of time set on the object.
     *
     * THIS SHOULD ALMOST ALWAYS BE USED INSTEAD OF COMMITTING DIRECTLY
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param messageObject
     * @returns {Promise<unknown>}
     */
    showMessage({dispatch, commit, getters}, messageObject) {
        return new Promise(((resolve, reject) => {
            if (getters.getIsAdmin && messageObject.showToChair === false) return resolve();

            if (!getters.getIsAdmin && messageObject.chairOnly === true) return resolve();

            commit('addToMessageQueue', messageObject);

            if (messageObject.displayTime > 0) {
                //set a timer and automatically remove
                window.console.log('setting timed message for ', messageObject.displayTime);
                setTimeout(function () {
                    window.console.log('message complete', messageObject);
                    commit('removeFromMessageQueue', messageObject)
                }, messageObject.displayTime)

            }
        }));
    },

    /**
     * When the server has responded with an error that defines
     * a message to show to the user, this handles showing them the message.
     *
     * @param dispatch
     * @param commit
     * @param getters
     * @param serverResponse
     * @returns {Promise<unknown>}
     */
    showServerProvidedMessage({dispatch, commit, getters}, serverResponse) {
        return new Promise(((resolve, reject) => {
            let m = Message.makeFromServerResponse(serverResponse);
            dispatch('showMessage', m).then(() => {
                resolve();
            });
        }));
    },


    // showMessage({dispatch, commit, getters}, {messageText, messageStyle, displayTime}) {
    //     return new Promise(((resolve, reject) => {
    //         commit('setMessageText', messageText);
    //         commit('setMessageStyle', messageStyle);
    //         commit('setDisplayTime', displayTime);
    //
    //         if(getters.getDisplayTime)
    //
    //     }));
    // },
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
    // getMessageText: (state) => {
    //     return state.messageText;
    // },
    // getMessageStyle: (state) => {
    //     return state.messageStyle;
    // },
    // getDisplayTime: (state) => {
    //     return state.displayTime;
    // },
    // getShowMessage: (state) => {
    //     state.showMessage;
    // },
    getMessages: (state) => {
        return state.messageQueue;
    }
};

export default {
    actions,
    getters,
    mutations,
    state,
}
