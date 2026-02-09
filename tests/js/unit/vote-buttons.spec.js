import {createStore} from 'vuex';
import {mount} from '@vue/test-utils';

// import {mount, shallowMount, createLocalVue} from '@vue/test-utils'
import VoteButtons from "../../../resources/js/components/vote-casting/vote-buttons.vue";

// import Vuex from 'vuex'
import sinon from 'sinon';


let actions = {};
let getters = {};
let mutations = {};
let state = {};

let store = createStore({
    actions, getters, mutations, state
});


describe('vote-buttons', () => {
    let wrapper;

    beforeEach(() => {
        // const wrapper = App = {
        //     VoteButtons,
        //     store
        // };

        const wrapper = mount(VoteButtons, {
            global: {
                plugins: [store]
            }
        })

    });


    test('placeholder ', () => {
        expect(1).toEqual(1);
    });


});

