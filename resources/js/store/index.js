/**
 * Created by adam on 2020-07-13.
 */


// import Vue from  'vue/dist/vue.js'
import Vue from 'vue'
import Vuex from 'vuex'

//global stuff
import * as actions from './actions'
import * as getters from './getters';
import * as mutations from './mutations'
import * as state from './state'

//modules
import chairUtilities from "./modules/chairUtilities";
import elections from "./modules/elections";
import meetings from './modules/meetings';
import modes from "./modules/modes";
import motions from './modules/motions';
import messages from "./modules/messages";
import provisionalMotions from "./modules/provisionalMotions";
import navigation from "./modules/navigation";
import startup from "./modules/startup";
import results from "./modules/results";
import votes from "./modules/votes";

Vue.use(Vuex);

/**
 * This subscribes the api package which
 * handles data exchange with the server
 * to mutations in the store.
 */
// import apiPlugin from '../api/apiPlugin';
// import websocketPlugin from '../api/websocketPlugin';


const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({

    strict: debug, //letting check determine whether to turn on or off. should be off for production to avoid performance hit

    /**
     * From instances and components where store has been
     * injected, actions are called
     * like so: store.dispatch( 'string-action-name' )
     */
    actions,
    getters,
    mutations,
    state,

    plugins: [], //[ apiPlugin, websocketPlugin ],

    modules: {
        chairUtilities,
        elections,
        meetings,
        messages,
        modes,
        motions,
        navigation,
        provisionalMotions,
        results,
        startup,
        votes
    }

// }
    // plugins: debug ? [createLogger()] : []
});
