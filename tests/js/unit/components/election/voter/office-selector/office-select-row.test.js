import {createStore} from 'vuex';
import {mount} from '@vue/test-utils';

import OfficeSelectRow
    from "../../../../../../../resources/js/components/election/voter/office-selector/office-select-row.vue";
import Office from "../../../../../../../resources/js/models/Office";

import {officeFactory} from "../../../../../modelFactories";

let store;
// let store = new Vuex.Store({
//     actions, getters, mutations, state
// });


describe('office-select-row', () => {
    let wrapper;
    let office;
    let actionSpy;

    beforeEach(() => {
        office = officeFactory();

        actionSpy = sinon.spy();

        store = createStore({
            actions: {
                setOfficeForVoting: actionSpy
            },
            getters: {
                getActiveMotion: (state) => {
                    return office;
                },
                hasVotedOnMotion: (state) => (motion) => {
                    return false;
                },

                showOverSelectionWarningForMotion: (state) => (motion) => {
                    return false;
                },
            },

            mutations: {},
            state: {}
        });

        wrapper = mount(OfficeSelectRow, {
            global: {
                plugins: [store]
            },
            propsData: {'motion': office}

        });

    });


    test('displays as expected', () => {

        expect(wrapper.html()).toContain(office.content);

        expect(wrapper.vm.motion.id).toBe(office.id);

    });

    test('dispatches expected action', async () => {
        await wrapper.get('[data-test="office-select-row"]').trigger('click');
        expect(actionSpy.calledOnce).toBe(true);
        expect(actionSpy.args[0][1].id).toBe(office.id);
    })

});
