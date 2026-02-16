import {createStore} from 'vuex';
import {mount, flushPromises} from '@vue/test-utils';

import axios from 'axios';
import MockAdapter from 'axios-mock-adapter'
// import {VueAxios} from 'vue-axios';

//
// import {mount, shallowMount, createLocalVue} from '@vue/test-utils'
// import Vuex from 'vuex'
import VoteVerificationPage from "../../../../../resources/js/components/main/vote-verification-page.vue";

import sinon from 'sinon';
//
// const localVue = createLocalVue()
// localVue.use(Vuex)
//
// let actions = {};
// let getters = {
//     getUsersCastVotes : sinon.spy(),
//
//     getMotionById: sinon.spy(),
//     getCandidateById : sinon.spy(),
//
// };
// let mutations = {};
// let state = {};
//
// let store = new Vuex.Store({
//     actions, getters, mutations, state
// });


describe('vote-verification-page.test', () => {
    let wrapper;
    let store;
    let $http;
    let actionSpy;
    let mock;


    beforeEach(() => {
        actionSpy = sinon.spy();
        // jest.spyOn(axios, 'post') //.mockResolvedValue({'status' : 404})

        store = createStore({
            getters: {
                getUsersCastVotes: sinon.spy(),

                getMotionById: sinon.spy(),
                getCandidateById: sinon.spy(),
            }


        });
        mock = new MockAdapter(axios);

        mock.onPost().reply(200, {receipt: faker.sha1, motionId : faker.number, isYay : 0});

        // $http = {
        //     post: actionSpy
        // };

        wrapper = mount(VoteVerificationPage, {
            global: {
                plugins: [store, mock],
            },
            propsData: {}

        });


    });

    test('displays as expected', () => {
        // expect(wrapper.html()).toContain('[id="receiptEntry"]');
        // const todo = wrapper.get('[data-test="verificationSubmit"]')
        // expect(wrapper.html()).toContain('[id="receiptEntry"]');

    });

    test('Shows button when receipt entered ', async () => {
        expect(wrapper.find('[data-test="receiptEntry"]').exists()).toBe(true);
        expect(wrapper.find('[data-test="verificationSubmit"]').exists()).toBe(false);

        let receipt = faker.sha1;
        await wrapper.find('[data-test="receiptEntry"]').setValue(receipt);
        expect(wrapper.find('[data-test="verificationSubmit"]').exists()).toBe(true);

    });


    test('submits receipt', async () => {
        // jest.spyOn(mock, 'post');//.mockResolvedValue({'status' : 404})

        let receipt = faker.sha1;
        await wrapper.find('[data-test="receiptEntry"]').setValue(receipt);
        expect(wrapper.find('[data-test="verificationSubmit"]').exists()).toBe(true);

        await wrapper.get('[data-test="verificationSubmit"]').trigger('click');
        expect(wrapper.receipt).toBe(receipt);

        // Wait until the DOM updates.
        await flushPromises()

        expect(wrapper.html()).toContain("The vote associated with this receipt is: Nay");

        // expect(axios.post()).toHaveBeenCalled();

        // Following lines tell Jest to mock any call to `axios.get`
// and to return `mockPostList` instead

        // expect(actionSpy.calledOnce).toBe(true);

    });


});
