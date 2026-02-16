import {createStore} from 'vuex';
import {mount} from '@vue/test-utils';
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


    beforeEach(() => {
        actionSpy = sinon.spy();

        store = createStore({
            getters: {
                getUsersCastVotes: sinon.spy(),

                getMotionById: sinon.spy(),
                getCandidateById: sinon.spy(),
            }


        });
        $http = {
            post: actionSpy
        };

        wrapper = mount(VoteVerificationPage, {
            global: {
                plugins: [store, $http],
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
        let receipt = faker.sha1;
        await wrapper.find('[data-test="receiptEntry"]').setValue(receipt);
        expect(wrapper.find('[data-test="verificationSubmit"]').exists()).toBe(true);

        await wrapper.get('[data-test="verificationSubmit"]').trigger('click');
        expect(actionSpy.calledOnce).toBe(true);

    });


});
