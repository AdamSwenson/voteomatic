import VoteButtons from  "../../../resources/js/components/vote-casting/vote-buttons.vue";

import {mount, shallowMount, createLocalVue} from '@vue/test-utils'
import Vuex from 'vuex'
import sinon from 'sinon';

const localVue = createLocalVue()
localVue.use(Vuex)

let actions = {};
let getters = {};
let mutations = {};
let state = {};

let store = new Vuex.Store({
    actions, getters, mutations, state
});


describe('vote-buttons', () => {
    let wrapper;

    beforeEach(() => {
        wrapper = shallowMount(VoteButtons, {
            store,
            localVue,
            propsData: {}
        });

    });


    test(' ', () => {
expect(1).toEqual(1);
    });


});

