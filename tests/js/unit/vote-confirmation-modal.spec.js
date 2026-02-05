let _ = require('lodash');
import sinon from 'sinon';

import {createStore} from 'vuex';
import {mount} from '@vue/test-utils';

import VoteConfirmationModal from "../../../resources/js/components/vote-casting/vote-confirmation-modal.vue";
import Motion from "../../../resources/js/models/Motion";
import Vote from "../../../resources/js/models/Vote";


// import {mount, shallowMount, createLocalVue} from '@vue/test-utils'
// import Vuex from 'vuex'
//
//
// const localVue = createLocalVue()
// localVue.use(Vuex)

let motion = new Motion({id: 4});

let actions = {
    castMotionVote: jest.fn(),
};

let getters = {
    getActiveMotion: () => {
        return motion;
    }
};

let store = createStore({
    actions, getters
});


describe('Yay votes', () => {

    let wrapper;
    beforeEach(() => {
        wrapper = mount(VoteConfirmationModal, {
            global: {
                plugins: [store]
            }
        })
    });

    test('beep', () => {
    actions.castMotionVote();
        expect(actions.castMotionVote).toHaveBeenCalled();

    });


    test('Yes dispatches castMotionVote ', () => {
        //dev This is failing because the program does not (no longer?) use castMotionVote
        wrapper.find('button.yes').trigger('click');
        expect(actions.castMotionVote).toHaveBeenCalled();

        //second arg of first call is the payload
        let arg = actions.castMotionVote.mock.calls[0][1];
        expect(arg).toBeInstanceOf(Vote);
        expect(arg._isYay).toEqual(true);
        expect(arg.motionId).toEqual(motion.id);
    });

    test('No does not dispatch castMotionVote ', () => {
        wrapper.find('button.no').trigger('click');
        expect(actions.castMotionVote).not.toHaveBeenCalled();
    });


});


describe('Nay votes', () => {

    let wrapper;
    beforeEach(() => {
       wrapper = mount(VoteConfirmationModal, {
            global: {
                plugins: [store]
            },
            propsData: {
                type: 'nay'
            }
        });
    });


    test('Yes dispatches castMotionVote ', () => {
        wrapper.find('button.yes').trigger('click');
        expect(actions.castMotionVote).toHaveBeenCalled();

        //second arg of first call is the payload
        let arg = actions.castMotionVote.mock.calls[0][1];
        expect(arg).toBeInstanceOf(Vote);
        expect(arg._isYay).toEqual(false);
        expect(arg.motionId).toEqual(motion.id);
    });

    test('No does not dispatch castMotionVote ', () => {
        wrapper.find('button.no').trigger('click');
        expect(actions.castMotionVote).not.toHaveBeenCalled();
    });


});
