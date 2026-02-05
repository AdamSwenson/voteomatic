import Vuex from 'vuex'
import actions from "../../../../../../resources/js/store/modules/motions/motions.actions.crud";
// import {mutations} from "../../../../../../resources/js/store/modules/motions/motions";
// import getters from "../../../../../../resources/js/store/modules/motions/motions";
import state from "../../../../../../resources/js/store/modules/motions/motions";

import {faker} from '@faker-js/faker';

import sinon from 'sinon';
import {motionFactory} from "../../../../modelFactories";

let getters = {};
let mutations = {
    deleteMotion: sinon.spy(),
    addMotionToStore: sinon.spy()
};

let store = new Vuex.Store({
    actions, getters, mutations, state
});


describe('motions.actions.crud.test', () => {

    let motions = [];

    beforeEach(() => {
        for (var i = 0; i < 10; i++) {
            motions.push(motionFactory())
        }

    });


    test('replaceMotionInStore ', () => {
        //prep
        store.state.motions = motions;
        let newContent = faker.lorem.paragraph();
        let motion = faker.helpers.arrayElement(motions);
        let originalContent = motion.content;
        expect(motion.content).not.toEqual(newContent);

        //call
        motion.content = newContent;
        store.dispatch('replaceMotionInStore', motion);

        //check
        mutations.deleteMotion.called;
        mutations.addMotionToStore.called;
        mutations.addMotionToStore.calledWith(motion);
        motion.content = originalContent;
        mutations.deleteMotion.calledWith(motion);


    });
});
