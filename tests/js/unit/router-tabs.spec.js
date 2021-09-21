import sinon from 'sinon';

import {mount, shallowMount, createLocalVue} from '@vue/test-utils'
import Vuex from 'vuex'
import Settings from "../../../resources/js/models/Settings";

const localVue = createLocalVue();
localVue.use(Vuex);

// import RouterTabs from "../../../resources/js/components/navigation/router-tabs.vue";

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

let store = new Vuex.Store({
    actions, getters
});


describe('methods', () => {

    let wrapper;
    beforeEach(() => {
        // wrapper = shallowMount(RouterTabs, {
        //     store, localVue,
        //     propsData: {}
        // });
    });

    test('passesSettingsChecks', () => {

        let settingsObj = new Settings({settings: {s1: true}});
        window.console.log(settingsObj);
        window.console.log(settingsObj.s1);
        let passesSettingsChecks = (route, settingsObj) => {
            //no settings are defined for the route
            // if (!isReadyToRock(route.showIfSettings)) return true;

            if (route.showIfSettings.length === 0) return true;

             return _.forEach(route.showIfSettings, (s) => {
                window.console.log(s, settingsObj.s1, settingsObj.isSettingTrue(s));
                if(settingsObj.isSettingTrue(s)) return true;
                // //dev or is is better to define by the condition being false?
                // if (settingsObj[s] === true) {
                //     return true;
                // } else {
                //     // return true;
                // }
            });

        };

        let route = {showIfSettings: ['s1']};


        expect(passesSettingsChecks(route, settingsObj)).toBe(true);
    });


    test('passesAdminCheck -- where is admin', () => {
        let route = {};
        route.adminOnly = isAdmin;

        // expect(wrapper.passesAdminCheck(route)).toBe(true);
    });

});

