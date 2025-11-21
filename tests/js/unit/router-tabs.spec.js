import sinon from 'sinon';

// import {mount, shallowMount, createLocalVue} from '@vue/test-utils'
// import Vuex from 'vuex'

import {createStore} from 'vuex';
import {mount} from '@vue/test-utils';

import Settings from "../../../resources/js/models/Settings";
import RouterTabs from "../../../resources/js/components/navigation/router-tabs.vue";
import Election from "../../../resources/js/models/Election";

// const localVue = createLocalVue();
// localVue.use(Vuex);

const App = {
    computed: {}
};


let isAdmin = true;

let actions = {};

let getters = {
    getIsAdmin: () => {
        return isAdmin;
    },
    getSettings: () => {
        return {};
    }
};

describe('router-tabs', () => {
    describe('electionTabs', () => {

        let wrapper;
        let store;
        let meeting;
        beforeEach(() => {
            meeting = new Election(1);
            store = createStore({
                actions, getters
            });

            wrapper = mount(RouterTabs, {
                global: {
                    plugins: [store]
                },

            });

        });

        test('shows expected routes - admin', () => {
            store = createStore({
                actions,
                getters : {
                    getIsAdmin :function(){
                        return true;
                    },
                    getMeeting: function () {
                        return meeting
                    },

                }
            });

            wrapper = mount(RouterTabs, {
                global: {
                    plugins: [store]
                },

            });

            expect(true).toBe(true);

            expect(wrapper.isElection).toBe(true);

        });


    });
//
//
    describe('methods', () => {
//
//     let wrapper;
//     beforeEach(() => {
//
//         wrapper = mount(App, {
//             global: {
//                 plugins: [store]
//             }
//         })
//         // wrapper = shallowMount(RouterTabs, {
//         //     store, localVue,
//         //     propsData: {}
//         // });
//     });
//
//     test('passesSettingsChecks', () => {
//
//         let settingsObj = new Settings({settings: {s1: true}});
//         window.console.log(settingsObj);
//         window.console.log(settingsObj.s1);
//         let passesSettingsChecks = (route, settingsObj) => {
//             //no settings are defined for the route
//             // if (!isReadyToRock(route.showIfSettings)) return true;
//
//             if (route.showIfSettings.length === 0) return true;
//
//              return _.forEach(route.showIfSettings, (s) => {
//                 window.console.log(s, settingsObj.s1, settingsObj.isSettingTrue(s));
//                 if(settingsObj.isSettingTrue(s)) return true;
//                 // //dev or is is better to define by the condition being false?
//                 // if (settingsObj[s] === true) {
//                 //     return true;
//                 // } else {
//                 //     // return true;
//                 // }
//             });
//
//         };
//
//         let route = {showIfSettings: ['s1']};
//
//
//         expect(passesSettingsChecks(route, settingsObj)).toBe(true);
//     });
//
//
//     test('passesAdminCheck -- where is admin', () => {
//         let route = {};
//         route.adminOnly = isAdmin;
//
//         // expect(wrapper.passesAdminCheck(route)).toBe(true);
//     });
    });
});

